import React, { useContext } from "react";
import FormSelect from "@modules/ui/forms/Select";
import { FormInput } from "@modules/account/components/shared/FormInput";
import { fillArrayItemsOnOrderActions } from "@modules/account/utils/fill-array-items-order-actions";
import { returnSelectValues } from "@modules/account/ts/consts/order-actions-select.const";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import { Formik, Form, FormikHelpers } from "formik";
import * as Yup from "yup";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";
import { openRMARequest } from "@redux/actions/account-actions/OrdersActions";
import { useDispatch } from "react-redux";
import UploadFile from "@modules/ui/UploadFile";
import validatorMaxFileSize from "@utils/yup/validatorMaxFileSize";
import validatorFileFormat from "@utils/yup/validatorFileFormat";
import Button from "@modules/ui/forms/Button";

interface IProps {
  orderItem: OrderView;
}

export const ReturnOrReplaceItems: React.FC<IProps> = (props: IProps) => {
  const { orderItem } = props;
  const { showSnackbar } = useContext(SnackbarContext);
  const dispatch = useDispatch();
  const [files, setFiles] = React.useState<File[]>([]);

  const initialValues = {
    rmaText: "",
    rmaItems: [],
    file: "",
  };

  const maxMB = 10;
  const supportedFormats = [
    "image/jpg",
    "image/jpeg",
    "image/png",
    "application/pdf",
  ];
  const inputFileRef = React.useRef<HTMLInputElement>(null);

  const validationSchema = Yup.object().shape({
    rmaText: Yup.string()
      .required("Message is required field")
      .max(250, "Remaining: 250 characters"),
    file: Yup.mixed()
      .test(
        "fileSize",
        `Maximum uploaded file size: ${maxMB} MB`,
        validatorMaxFileSize(inputFileRef, maxMB)
      )
      .test(
        "fileType",
        "Unsupported File Format",
        validatorFileFormat(inputFileRef, supportedFormats)
      ),
  });

  function submit(values: Record<any, any>, actions: FormikHelpers<any>) {
    actions.setSubmitting(true);

    const fd = new FormData();

    for (let i = 0; i < files.length; i++) {
      fd.append(`files[${i}]`, files[i]);
    }

    fd.append("orderId", orderItem.orderId);
    fd.append("rmaText", values.rmaText);

    const items = values.rmaItems.map((e) => {
      return {
        productId: e.productid,
        amount: e.amountSelect.value,
        wouldLike: e.quantitySelect.value,
      };
    });

    fd.append("items", JSON.stringify(items));

    dispatch(
      openRMARequest({
        data: fd,
        success() {
          actions.setSubmitting(false);

          showSnackbar({
            header: "Success",
            message: `Thank you for your rma request!`,
            theme: "success",
          });
          actions.resetForm();
          setFiles([]);
        },
      })
    );
  }

  const updateValueOnReturnItems = (value, id, values, setValues) => {
    if (values.rmaItems.find((e: any) => e.productId == id)) {
      setValues({
        rmaItems: values.rmaItems.map((e: any) => {
          if (e.productId === id) return { ...e, ...value };
          return e;
        }),
      });

      return;
    }

    setValues({
      rmaItems: values.rmaItems.concat(value),
    });
  };

  const getProductItem = (id, values) => {
    return values.rmaItems.find((e) => e.productId == id);
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

      <Formik
        initialValues={initialValues}
        validationSchema={validationSchema}
        onSubmit={submit}
      >
        {({
          isSubmitting,
          handleChange,
          values,
          errors,
          touched,
          setValues,
        }) => {
          return (
            <Form>
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
                          <FormSelect
                            value={
                              getProductItem(product.productId, values)
                                ?.amountSelect || {
                                value: 0,
                                viewValue: 0,
                              }
                            }
                            items={fillArrayItemsOnOrderActions(product.amount)}
                            id={`${product.code}-amount`}
                            onClick={(value) =>
                              updateValueOnReturnItems(
                                { ...product, amountSelect: value },
                                product.productId,
                                values,
                                setValues
                              )
                            }
                          />
                        </div>

                        <div className="order-product-list-header-quantity-cancel">
                          <FormSelect
                            classes={{
                              selectHeader:
                                "order-product-select-action-header",
                            }}
                            value={
                              getProductItem(product.productId, values)
                                ?.quantitySelect || {
                                value: undefined,
                                viewValue: "Select an option",
                              }
                            }
                            onClick={(value) =>
                              updateValueOnReturnItems(
                                { ...product, quantitySelect: value },
                                product.productId,
                                values,
                                setValues
                              )
                            }
                            id={`${product.code}-action`}
                            items={returnSelectValues}
                          />
                        </div>
                      </div>
                    ));
                  })}
                </div>
                <FormInput
                  inputType="textarea"
                  name={"rmaText"}
                  id={"132"}
                  placeholder="Explain why you would like to return products for a refund or replace them with the same or different products"
                  classes={{
                    input: "order-cancel-items-textarea-input",
                    textArea: "order-cancel-items-textarea",
                    group: "order-cancel-items-textarea-group",
                  }}
                  handleChange={handleChange}
                  errorMessage={errors.rmaText}
                  touched={touched.rmaText}
                  value={values.rmaText}
                />

                <div className="order-cancel-items-disclosure-title attach-section">
                  Please attach product images to speed up the RMA process:
                </div>

                <UploadFile
                  classNames="mt-12 mt-md-14 mb-10 mb-md-3"
                  files={files}
                  setFiles={setFiles}
                  ref={inputFileRef}
                  formats={supportedFormats}
                  maxSize={maxMB}
                  multiple
                  name="file"
                  onChange={handleChange}
                  error={errors.file}
                />

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
                    Our RMA department will work with the warehouse to resolve
                    your problem.
                  </div>
                </div>

                <Button
                  disabled={isSubmitting}
                  className="form-button submit-to-rma-dep-btn"
                >
                  Submit to RMA department
                </Button>
              </div>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};
