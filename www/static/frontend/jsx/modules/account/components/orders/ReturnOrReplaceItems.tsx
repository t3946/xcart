import React, { useContext, useState } from "react";
import { FormSelect } from "@client/modules/account/components/shared/FormSelect";
import { FormInput } from "@client/modules/account/components/shared/FormInput";
import { fillArrayItemsOnOrderActions } from "@client/modules/account/utils/fill-array-items-order-actions";
import { returnSelectValues } from "@client/modules/account/ts/consts/order-actions-select.const";
import { FileDrop } from "@client/modules/account/components/shared/FileDrop";
import { FileItem } from "@client/modules/account/components/orders/FileItem";
import { ApiService } from "@client/modules/shared/services/api.service";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import { useParams } from "react-router-dom";
import { OrderPageURLParams } from "@client/modules/account/ts/types/order-page-url-params.type";
import { useFormik } from "formik";
import * as Yup from "yup";

interface ReturnOrReplaceItemProps {
  orderItem: any;
}

export const ReturnOrReplaceItems: React.FC<ReturnOrReplaceItemProps> = ({
  orderItem,
}) => {
  const urlParams = useParams<OrderPageURLParams>();

  const onDrop = ([acceptedFile]) => {
    setFiles((prev) =>
      prev.concat({
        id: files.length === 0 ? 0 : prev[prev.length - 1].id + 1,
        file: acceptedFile,
      })
    );
  };
  const api = new ApiService();

  const [loading, setLoading] = useState(false);

  const { showSnackbar } = useContext(SnackbarContext);

  const openRequest = () => {
    setLoading(true);
    api
      .post(
        "/account/api/orders/open-rma-request",
        JSON.stringify({
          rma_info: {
            orderid: urlParams.id,
            zipcode: orderItem.orderInfo.s_zipcode,
            email: orderItem.orderInfo.email,
            date: orderItem.orderInfo.date,
            status: 3,
            rmanumber: 0,
            orderemail: orderItem.orderInfo.email,
            explanation: formik.values.rmaText,
          },
          rma_items: formik.values.rmaItems.map((e) => {
            return {
              itemid: e.itemid,
              productid: e.productid,
              productcode: e.productcode,
              product: e.product,
              would_like: e.quantitySelect.value,
              amount: e.amountSelect.value,
            };
          }),
        })
      )
      .then(() => {
        setLoading(false);
        showSnackbar({
          header: "Success",
          message: `Thank you for your rma request!`,
          theme: "success",
        });
        formik.resetForm();
      });
  };

  const formik = useFormik({
    initialValues: {
      rmaText: "",
      rmaItems: [],
    },
    validationSchema: Yup.object().shape({
      rmaText: Yup.string()
        .required("Required field")
        .max(250, "Remaining: 250 characters"),
    }),
    onSubmit: openRequest,
  });

  const [files, setFiles] = useState([]);

  const updateValueOnReturnItems = (value, id) => {
    if (formik.values.rmaItems.find((e) => e.productid === id)) {
      formik.setFieldValue(
        "rmaItems",
        formik.values.rmaItems.map((e) => {
          if (e.productid === id) return { ...e, ...value };
          return e;
        })
      );
      return;
    }
    formik.setFieldValue("rmaItems", formik.values.rmaItems.concat(value));
  };

  const getProductItem = (id) => {
    return formik.values.rmaItems.find((e) => e.productid === id);
  };
  return (
    <div className="order-product-list-body-inner">
      <div className="page-label order-actions-page-label">
        Return or replace items
      </div>
      <div className="order-product-list-header">
        <div className="order-product-list-header-sku">Item name / SKU </div>
        <div className="order-product-list-header-quantity">
          Return quantity
        </div>
        <div className="order-product-list-header-quantity-cancel">
          I would like to
        </div>
      </div>
      <form onSubmit={formik.handleSubmit}>
        <div className="order-product-list-body">
          <div className="order-products">
            {orderItem.orderGroups.map((group) => {
              return group.orderGroupsItems.map((e) => {
                return (
                  <div className="order-product">
                    <div className="order-product-list-header-sku">
                      <div className="order-item-body-product-name">
                        {e.product}
                      </div>
                      <div className="order-item-body-product-sku">
                        {e.productcode}
                      </div>
                    </div>
                    <div className="order-product-list-header-quantity">
                      <FormSelect
                        classes={{ group: "order-product-select-count" }}
                        value={
                          getProductItem(e.productid)?.amountSelect || {
                            value: 0,
                            viewValue: 0,
                          }
                        }
                        items={fillArrayItemsOnOrderActions(e.amount)}
                        id={`${e.productcode}-amount`}
                        onClick={(value) =>
                          updateValueOnReturnItems(
                            { ...e, amountSelect: value },
                            e.productid
                          )
                        }
                      />
                    </div>
                    <div className="order-product-list-header-quantity-cancel">
                      <FormSelect
                        classes={{
                          group: "order-product-select-action",
                          selectHeader: "order-product-select-action-header",
                        }}
                        value={
                          getProductItem(e.productid)?.quantitySelect || {
                            value: undefined,
                            viewValue: "Select an option",
                          }
                        }
                        onClick={(value) =>
                          updateValueOnReturnItems(
                            { ...e, quantitySelect: value },
                            e.productid
                          )
                        }
                        id={`${e.productcode}-action`}
                        items={returnSelectValues}
                      />
                    </div>
                  </div>
                );
              });
            })}
          </div>
          <FormInput
            inputType="textarea"
            name={"rmaText"}
            id={"132"}
            placeholder="Explain why you would like to return products for a refund
          or replace them with the same or different products"
            classes={{
              input: "order-cancel-items-textarea-input",
              textArea: "order-cancel-items-textarea",
              group: "order-cancel-items-textarea-group",
            }}
            handleChange={formik.handleChange}
            errorMessage={formik.errors.rmaText}
            handleBlur={formik.handleBlur}
            touched={formik.touched.rmaText}
            value={formik.values.rmaText}
          />
          <div className="order-cancel-items-disclosure-title attach-section">
            Please attach product images to speed up the RMA process:
          </div>
          <FileDrop onDrop={onDrop}>
            <button className="choose-file-btn" onClick={open}>
              Choose file
            </button>
          </FileDrop>
          {files.map((e: { id: number; file: File }) => (
            <FileItem
              key={e.id}
              file={e.file}
              onClick={() => setFiles(files.filter((file) => file.id !== e.id))}
            />
          ))}

          <div className="order-cancel-items-disclosure">
            <div className="order-cancel-items-disclosure-title">
              Disclosure
            </div>
            <div className="order-cancel-items-disclosure-subtitle">
              1. Do not send the product back. Wait for the RMA form.
            </div>
            <div className="order-cancel-items-disclosure-subtitle">
              2. We can’T guarantee successful resolution of your request.
            </div>
            <div className="order-cancel-items-disclosure-subtitle">
              Our RMA department will work with the warehouse to resolve your
              problem.
            </div>
          </div>
          <button
            disabled={loading}
            className="form-button submit-to-rma-dep-btn"
            type={"submit"}
          >
            Submit to RMA department
          </button>
        </div>
      </form>
    </div>
  );
};
