import React, { useContext, useState } from "react";
import cn from "classnames";
import FormSelect from "@modules/ui/forms/Select";
import { fillArrayItemsOnOrderActions } from "@modules/account/utils/fill-array-items-order-actions";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import { ApiService } from "@modules/shared/services/api.service";
import { useFormik } from "formik";
import * as Yup from "yup";
import { useRouter } from "next/router";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";
import OrderTable from "@modules/account/components/order/order-table/OrderTable";
import ProductCell from "@modules/account/components/order/order-table/ProductCell";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";

import StylesOrderActions from "@modules/account/components/orders/OrderActions.module.scss";
import Styles from "@modules/account/components/orders/CancelItems.module.scss";

interface IProps {
  orderItem: OrderView;
}

export const CancelItems: React.FC<IProps> = (props) => {
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
    <>
      <div className={cn("order-product-list-body-inner p-lg-0")}>
        <div
          className={cn(
            "page-label",
            "problem-with-order-label",
            "text-md-start",
            StylesOrderActions.title,
            "mb-lg-20"
          )}
        >
          Cancel items
        </div>
        <OrderTable
          theme="red"
          header={[
            <span>Item name / SKU</span>,
            <>
              <span className="d-none d-lg-block">Quantity Ordered</span>
              <span className="d-none d-md-block d-lg-none">Qty Ordered</span>
            </>,
            <>
              <span className="d-none d-lg-block">Quantity to cancel</span>
              <span className="d-lg-none">Qty to cancel</span>
            </>,
          ]}
          items={orderItem.groups[0].products}
          classes={{
            table: ["px-md-2", "px-lg-0", StylesOrderActions.form__table],
            rowHat: StylesOrderActions.tableRow_hat,
            row: StylesOrderActions.tableRow,
            columns: ["col-sm me-auto", "col-md-3", "col-5 col-sm-3"],
          }}
          rowItemTemplates={(item) => {
            return [
              <>
                <ProductCell name={item.product} sku={item.code} />
                <div className="d-md-none mt-10">
                  Qty Ordered: {item.amount}
                </div>
              </>,

              <span className="d-none d-md-block">{item.amount}</span>,

              <div className=" ms-auto col-9 col-sm-6">
                <FormSelect
                  classes={{ group: "order-product-select-count" }}
                  value={
                    getProductItem(item.product)?.amount || {
                      value: 0,
                      viewValue: 0,
                    }
                  }
                  items={fillArrayItemsOnOrderActions(item.amount)}
                  id={item.code}
                  onClick={(value) =>
                    updateValueOnCancelItems(value, item.productId)
                  }
                />
              </div>,
            ];
          }}
        />
        <div className="px-10 px-lg-0">
          <Input
            as="textarea"
            name={"cancelText"}
            placeholder="Explain why you would like to cancel items"
            onChange={formik.handleChange}
            isInvalid={
              !!formik.touched.cancelText && !!formik.errors.cancelText
            }
            isValid={!!formik.touched.cancelText && !formik.errors.cancelText}
            className={cn(
              StylesOrderActions.problemTextArea,
              StylesOrderActions.form__problemTextArea
            )}
          />
          <Feedback>
            {!!formik.touched.cancelText && formik.errors.cancelText}
          </Feedback>

          <button
            disabled={!formik.values.cancelItemsValues.length || loading}
            className={cn(
              "form-button",
              "request-cancellation-btn",
              "w-100",
              "w-md-auto",
              "mx-auto",
              "mx-lg-0",
              StylesOrderActions.button
            )}
            onClick={openRequest}
          >
            {loading ? "sending request..." : "REQUEST CANCELLATION"}
          </button>
          <div
            className={cn(
              "order-cancel-items-disclosure",
              Styles.order__diclosure
            )}
          >
            <div
              className={cn(
                "order-cancel-items-disclosure-title",
                StylesOrderActions.disclosureTitle,
                "mb-md-10"
              )}
            >
              Disclosure
            </div>
            <div
              className={cn(
                "order-cancel-items-disclosure-subtitle",
                StylesOrderActions.disclosureSubtitle
              )}
            >
              We’ll try our best to cancel the items, however cancellation is
              not guaranteed.
            </div>
          </div>
        </div>
      </div>
    </>
  );
};
