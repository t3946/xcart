import React, { useState } from "react";
import cn from "classnames";
import Select from "@modules/ui/forms/select/Select";
import { fillArrayItemsOnOrderActions } from "@modules/account/utils/fill-array-items-order-actions";
import useSnackbar from "@modules/account/hooks/useSnackbar";
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
  products: any;
}

export const CancelItems: React.FC<IProps> = (props) => {
  const { orderItem, products } = props;
  const [loading, setLoading] = useState(false);
  const router = useRouter();
  const snackbar = useSnackbar();

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
          items: Object.values(formik.values.cancelItemsValues).map((e) => {
            return { ...e, amount: e.cancelAmount?.value };
          }),
        })
      )
      .then(() => {
        setLoading(false);
        formik.setSubmitting(false);
        snackbar.show(
          `Thank you for your cancellation request! We’ll try our best to cancel the items.`,
          10000
        );
        window.scroll(0, 120);
        formik.resetForm();
      });
  };

  const formik = useFormik({
    initialValues: {
      cancelText: "",
      cancelItemsValues: products.reduce(
        (initObj, product) => ({ ...initObj, [product.productId]: product }),
        {}
      ),
    },
    validationSchema: Yup.object().shape({
      cancelText: Yup.string()
        .required("Required field")
        .max(250, "Remaining: 250 characters"),
    }),
    onSubmit: openRequest,
  });

  const updateValueOnCancelItems = (value, id) => {
    formik.values.cancelItemsValues[id] = {
      ...formik.values.cancelItemsValues[id],
      cancelAmount: value,
    };
    formik.setFieldValue("cancelItemsValues", formik.values.cancelItemsValues);
  };

  const getProductItem = (id) => {
    return formik.values.cancelItemsValues[id];
  };

  return (
    <form
      onSubmit={formik.handleSubmit}
      encType="multipart/form-data"
      className={cn("order-product-list-body-inner p-lg-0")}
    >
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
        items={formik.values.cancelItemsValues}
        classes={{
          table: ["px-md-2", "px-lg-0", StylesOrderActions.form__table],
          rowHat: StylesOrderActions.tableRow_hat,
          row: StylesOrderActions.tableRow,
          columns: [
            "col-sm me-auto",
            "col-md-3",
            "col-5 col-sm-3",
            "text-center",
            "text-md-end",
          ],
        }}
        rowItemTemplates={(item) => {
          return [
            <>
              <ProductCell name={item.product} sku={item.code} url={item.url} />
              <div className="d-md-none mt-10">Qty Ordered: {item.amount}</div>
            </>,

            <span className="d-none d-md-block">{item.amount}</span>,

            <div className=" ms-auto col-9 col-sm-6">
              <Select
                clearable={false}
                classes={{
                  select: "order-product-select-count",
                  control: "p-0",
                }}
                value={
                  getProductItem(item.productId)?.cancelAmount || {
                    value: 0,
                    label: 0,
                  }
                }
                options={fillArrayItemsOnOrderActions(item.amount)}
                onChange={(e) =>
                  updateValueOnCancelItems(e.target.value, item.productId)
                }
              />
            </div>,
          ];
        }}
      />
      <div className="px-10 px-lg-0">
        <div className="position-relative mb-4">
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
          <Feedback
            type="invalid"
            className="d-block top-100 position-absolute"
          >
            {!!formik.touched.cancelText && formik.errors.cancelText}
          </Feedback>
        </div>

        <button
          type="submit"
          disabled={
            !Object.values(formik.values.cancelItemsValues).filter(
              (value) => value.cancelAmount && value.cancelAmount.value
            ).length || formik.isSubmitting
          }
          className={cn(
            "form-button",
            "request-cancellation-btn",
            "w-100",
            "w-md-auto",
            "mx-auto",
            "mx-lg-0",
            StylesOrderActions.button
          )}
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
            We’ll try our best to cancel the items, however cancellation is not
            guaranteed.
          </div>
        </div>
      </div>
    </form>
  );
};
