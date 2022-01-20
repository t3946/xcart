import React, { useContext, useState } from "react";
import FormSelect from "@modules/ui/forms/Select";
import { FormInput } from "@modules/account/components/shared/FormInput";
import { fillArrayItemsOnOrderActions } from "@modules/account/utils/fill-array-items-order-actions";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import { ApiService } from "@modules/shared/services/api.service";
import { useFormik } from "formik";
import * as Yup from "yup";
import { useRouter } from "next/router";

export const CancelItems: React.FC = (props) => {
  const { orderItem } = props;
  const [loading, setLoading] = useState(false);
  const router = useRouter();
  const { showSnackbar } = useContext(SnackbarContext);

  const api = new ApiService();

  const openRequest = () => {
    setLoading(true);
    api
      .post(
        "/api/account/orders/open-cancel-request",
        JSON.stringify({
          order: {
            order_id: router.query.id,
            cancel_text: formik.values.cancelText,
          },
          items: formik.values.cancelItemsValues.map((e) => {
            return { ...e, amount: e.amount.value };
          }),
        })
      )
      .then(() => {
        setLoading(false);
        showSnackbar({
          header: "Success",
          message: `Thank you for your cancellation request! We’ll try our best to cancel the items.`,
          theme: "success",
        });
        formik.resetForm();
      });
  };

  const formik = useFormik({
    initialValues: {
      cancelText: "",
      cancelItemsValues: [],
    },
    validationSchema: Yup.object().shape({
      cancelText: Yup.string()
        .required("Required field")
        .max(250, "Remaining: 250 characters"),
    }),
    onSubmit: openRequest,
  });

  const updateValueOnCancelItems = (value, id) => {
    if (formik.values.cancelItemsValues.find((e) => e.order_item_id === id)) {
      if (value.value === 0) {
        formik.setFieldValue(
          "cancelItemsValues",
          formik.values.cancelItemsValues.filter((e) => e.order_item_id !== id)
        );
        return;
      }
      if (value.value === 0) {
        return;
      }
      formik.setFieldValue(
        "cancelItemsValues",
        formik.values.cancelItemsValues.map((e) => {
          if (e.order_item_id === id)
            return {
              ...e,
              amount: value,
            };
          return e;
        })
      );
      return;
    }
    formik.setFieldValue(
      "cancelItemsValues",
      formik.values.cancelItemsValues.concat({
        order_item_id: id,
        amount: value,
      })
    );
  };

  const getProductItem = (id) => {
    return formik.values.cancelItemsValues.find((e) => e.order_item_id === id);
  };

  return (
    <div className="order-product-list-body-inner">
      <div className="page-label order-actions-page-label">Cancel items</div>
      <div className="order-product-list-header">
        <div className="order-product-list-header-sku">Item name / SKU </div>
        <div className="order-product-list-header-quantity">
          Quantity Ordered
        </div>
        <div className="order-product-list-header-quantity-cancel">
          Quantity to cancel
        </div>
      </div>
      <div className="order-product-list-body">
        <div className="order-products">
          {orderItem.groups.map((group) => {
            return group.products?.map((product) => (
              <div key={product.productId} className="order-product">
                <div className="order-product-list-header-sku">
                  <div className="order-item-body-product-name">
                    {product.product}
                  </div>
                  <div className="order-item-body-product-sku">
                    {product.code}
                  </div>
                </div>
                <div className="order-product-list-header-quantity">
                  {product.amount}
                </div>
                <div className="order-product-list-header-quantity-cancel">
                  <FormSelect
                    classes={{ group: "order-product-select-count" }}
                    value={
                      getProductItem(product.product)?.amount || {
                        value: 0,
                        viewValue: 0,
                      }
                    }
                    items={fillArrayItemsOnOrderActions(product.amount)}
                    id={product.code}
                    onClick={(value) =>
                      updateValueOnCancelItems(value, product.productId)
                    }
                  />
                </div>
              </div>
            ));
          })}
        </div>
        <FormInput
          inputType="textarea"
          name={"cancelText"}
          handleChange={formik.handleChange}
          errorMessage={formik.errors.cancelText}
          handleBlur={formik.handleBlur}
          touched={formik.touched.cancelText}
          value={formik.values.cancelText}
          id={"132"}
          placeholder="Explain why you would like to cancel items"
          classes={{
            input: "order-cancel-items-textarea-input",
            textArea: "order-cancel-items-textarea",
            group: "order-cancel-items-textarea-group",
          }}
        />
        <button
          disabled={!formik.values.cancelItemsValues.length || loading}
          className="form-button request-cancellation-btn"
          onClick={openRequest}
        >
          {loading ? "sending request..." : "REQUEST CANCELLATION"}
        </button>
        <div className="order-cancel-items-disclosure">
          <div className="order-cancel-items-disclosure-title">Disclosure</div>
          <div className="order-cancel-items-disclosure-subtitle">
            We’ll try our best to cancel the items, however cancellation is not
            guaranteed.
          </div>
        </div>
      </div>
    </div>
  );
};
