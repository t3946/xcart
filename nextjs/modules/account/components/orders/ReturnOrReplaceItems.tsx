import React, { useContext } from "react";
import cn from "classnames";
import Select from "@modules/ui/forms/select/Select";
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
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import OrderTable from "@modules/account/components/order/order-table/OrderTable";
import ProductCell from "@modules/account/components/order/order-table/ProductCell";
import RadioQuestion from "@modules/account/components/orders/Decision/LTLFreightShipment/RadioQuestion";
import StylesOrderActions from "@modules/account/components/orders/OrderActions.module.scss";
import Styles from "@modules/account/components/orders/ReturnOrReplaceItems.module.scss";

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
        Return or replace items
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
              <OrderTable
                theme="grey"
                header={[
                  <span>Item name / SKU</span>,
                  <>
                    <span className="d-none d-lg-block">Return quantity</span>
                    <span className="d-lg-none">Return Qty</span>
                  </>,
                  <span className="d-none d-md-block">I would like to</span>,
                ]}
                items={orderItem.groups[0].products}
                classes={{
                  table: ["px-md-2", "px-lg-0", StylesOrderActions.form__table],
                  rowHat: StylesOrderActions.tableRow_hat,
                  row: [
                    "flex-wrap",
                    "flex-md-nowrap",
                    StylesOrderActions.tableRow,
                  ],
                  columns: [
                    "col-8 col-md-auto col-sm me-auto",
                    "col-4 col-lg-3",
                    "col-md-4 col-lg-3",
                    "col-12 col-md-auto",
                  ],
                }}
                rowItemTemplates={(item) => {
                  return [
                    <ProductCell name={item.product} sku={item.code} />,

                    <div className="col-9 col-md-6 mx-auto">
                      <Select
                        classes={{ control: "p-0" }}
                        clearable={false}
                        value={
                          getProductItem(item.productId, values)
                            ?.amountSelect || {
                            value: 0,
                            label: 0,
                          }
                        }
                        options={fillArrayItemsOnOrderActions(item.amount)}
                        onChange={(e) =>
                          updateValueOnReturnItems(
                            { ...item, amountSelect: e.target.value },
                            item.productId,
                            values,
                            setValues
                          )
                        }
                      />
                    </div>,

                    <div className="d-none d-md-block col-md-11 col-lg-10 col-xl-10 col-xxl-8 ms-auto">
                      <Select
                        classes={{ control: "p-0" }}
                        clearable={false}
                        value={
                          getProductItem(item.productId, values)
                            ?.quantitySelect || {
                            value: undefined,
                            label: "Select an option",
                          }
                        }
                        onChange={(e) =>
                          updateValueOnReturnItems(
                            { ...item, quantitySelect: e.target.value },
                            item.productId,
                            values,
                            setValues
                          )
                        }
                        options={returnSelectValues}
                      />
                    </div>,
                    <RadioQuestion
                      classes={{
                        container: [
                          "d-md-none col-12 text-start mt-4 border-0",
                          Styles.question,
                          Styles.tableRow__question,
                        ],
                        card: "box-shadow-0",
                      }}
                      question={{
                        label: "I would like to",
                        radios: returnSelectValues,
                      }}
                      checkedValues={{
                        "I would like to": getProductItem(
                          item.productId,
                          values
                        )?.quantitySelect || {
                          value: undefined,
                          label: "Select an option",
                        },
                      }}
                      disabled={isSubmitting}
                      onChange={(e) =>
                        updateValueOnReturnItems(
                          { ...item, quantitySelect: e.target.value },
                          item.productId,
                          values,
                          setValues
                        )
                      }
                    />,
                  ];
                }}
              />
              <div className="px-10 px-lg-0">
                <Input
                  as="textarea"
                  name={"problem_text"}
                  onChange={handleChange}
                  disabled={isSubmitting}
                  placeholder="Explain why you would like to return products for a refund
                  or replace them with the same or different products"
                  value={values.problem_text}
                  isValid={touched.problem_text && !errors.problem_text}
                  isInvalid={!!touched.problem_text && !!errors.problem_text}
                  className={cn("mt-4", StylesOrderActions.problemTextArea)}
                />
                <Feedback type="invalid">
                  {!!touched.problem_text && errors.problem_text}
                </Feedback>

                <div
                  className={cn(
                    "order-cancel-items-disclosure-title",
                    "attach-section",
                    Styles.disclosureTitle
                  )}
                >
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

                <div
                  className={cn(
                    "order-cancel-items-disclosure",
                    StylesOrderActions.order__diclosure
                  )}
                >
                  <div
                    className={cn(
                      "order-cancel-items-disclosure-title",
                      StylesOrderActions.disclosureTitle,
                      "mb-md-10",
                      "mb-lg-1"
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
                    <div>
                      1. Do not send the product back. Wait for the RMA form.
                    </div>
                    <div>
                      2. We can’T guarantee successful resolution of your
                      request.
                    </div>
                    <div>
                      Our RMA department will work with the warehouse to resolve
                      your problem.
                    </div>
                  </div>
                </div>

                <button
                  disabled={isSubmitting}
                  className={cn(
                    "form-button",
                    "submit-to-rma-dep-btn",
                    "w-md-auto",
                    "mx-md-auto",
                    "mx-lg-0",
                    StylesOrderActions.button,
                    "mb-4"
                  )}
                >
                  <span className={"d-lg-none"}>REQUEST CANCELLATION</span>
                  <span className={"d-none d-lg-block"}>
                    Submit to RMA department
                  </span>
                </button>
              </div>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};
