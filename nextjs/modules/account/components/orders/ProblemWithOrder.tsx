import React, { useContext, useState } from "react";
import { FormSelect } from "@modules/account/components/shared/FormSelect";
import { FormInput } from "@modules/account/components/shared/FormInput";
import { problemsWithOrderSelectValue } from "@modules/account/ts/consts/order-actions-select.const";
import { ApiService } from "@modules/shared/services/api.service";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import { useFormik } from "formik";
import * as Yup from "yup";
import { useParams } from "react-router-dom";
import { OrderPageURLParams } from "@modules/account/ts/types/order-page-url-params.type";
import { AddressItemDto } from "@modules/account/ts/types/address-item.type";
import { RadioBtn } from "@modules/account/components/shared/RadioBtn";

interface ProblemWithOrderProps {}

export const ProblemWithOrder: React.FC<ProblemWithOrderProps> = () => {
  const api = new ApiService();

  const urlParams = useParams<OrderPageURLParams>();

  const [loading, setLoading] = useState(false);

  const { showSnackbar } = useContext(SnackbarContext);

  const sendMessage = () => {
    setLoading(true);
    api
      .post(
        "/account/api/orders/send-problem-message",
        JSON.stringify({
          ...formik.values,
          problem_status: String(formik.values.problem_status.value),
        })
      )
      .then(() => {
        setLoading(false);
        showSnackbar({
          header: "Success",
          message: `Thank you for reporting the problem! We’ll address it ASAP.`,
          theme: "success",
        });
        formik.resetForm();
      });
  };

  const formik = useFormik({
    initialValues: {
      order_id: urlParams.id,
      problem_status: problemsWithOrderSelectValue[0],
      problem_text: "",
    },
    validationSchema: Yup.object().shape({
      problem_text: Yup.string()
        .required("Required field")
        .max(250, "Remaining: 250 characters"),
    }),
    onSubmit: sendMessage,
  });

  return (
    <div className="order-product-list-body-inner">
      <div className="page-label order-actions-page-label problem-with-order-label">
        Problem with order
      </div>
      <p className="what-went-wrong">What went wrong?</p>
      <form onSubmit={formik.handleSubmit}>
        <FormSelect
          classes={{ group: "order-product-select-errors" }}
          value={formik.values.problem_status}
          items={problemsWithOrderSelectValue}
          onClick={(value) => formik.setFieldValue("problem_status", value)}
          id={"problem-with-order-select"}
        />
        <div className="order-problems-radios">
          {problemsWithOrderSelectValue.map((e: any, index) => {
            return (
              <RadioBtn
                name="radio"
                id={index}
                viewValue={e.viewValue}
                groupValue={formik.values.problem_status.value}
                radioValue={e.value}
                onChange={(value) =>
                  formik.setFieldValue("problem_status", {
                    value: value,
                    viewValue: e.viewValue,
                  })
                }
                groupClasses={{
                  group: "order-problem-radio",
                  checked: "order-problem-radio-checked",
                }}
              />
            );
          })}
        </div>

        <FormInput
          inputType="textarea"
          name={"problem_text"}
          id={"132"}
          handleChange={formik.handleChange}
          errorMessage={formik.errors.problem_text}
          handleBlur={formik.handleBlur}
          touched={formik.touched.problem_text}
          value={formik.values.problem_text}
          placeholder="Explain why you would like to return products for a refund
        or replace them with the same or different products"
          classes={{
            input: "order-cancel-items-textarea-input",
            textArea: "order-cancel-items-textarea",
            group: "order-cancel-items-textarea-group",
          }}
        />
        <button
          disabled={loading}
          type="submit"
          className="form-button problem-with-order-send-btn"
        >
          send
        </button>
      </form>
    </div>
  );
};
