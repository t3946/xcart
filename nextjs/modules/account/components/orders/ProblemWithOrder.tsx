import React, { useContext, useEffect, useState } from "react";
import FormSelect from "@modules/ui/forms/Select";
import { FormInput } from "@modules/account/components/shared/FormInput";
import { ApiService } from "@modules/shared/services/api.service";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import { useFormik } from "formik";
import * as Yup from "yup";
import { RadioBtn } from "@modules/account/components/shared/RadioBtn";
import { useRouter } from "next/router";
import { problemsWithOrderSelectValue } from "@modules/account/ts/consts/order-actions-select.const";

export const ProblemWithOrder: React.FC = () => {
  const api = new ApiService();
  const router = useRouter();
  const [statuses, setStatuses] = useState(problemsWithOrderSelectValue);

  const [loading, setLoading] = useState(false);

  const { showSnackbar } = useContext(SnackbarContext);
  useEffect(() => {
    api
      .get("/api/account/orders/get-problem-statuses")
      .then((res) => setStatuses(res));
  }, []);

  const sendMessage = () => {
    setLoading(true);
    api
      .post(
        "/api/account/orders/send-problem-message",
        JSON.stringify({
          ...formik.values,
          status_id: String(formik.values.status_id.value),
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
      order_id: router.query.id,
      status_id: statuses[0],
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
          value={formik.values.status_id}
          items={statuses}
          onClick={(value) => formik.setFieldValue("status_id", value)}
          id={"problem-with-order-select"}
        />
        <div className="order-problems-radios">
          {statuses.map(
            (e, i) => "<RadioBtn /> was here"
            // TODO: делает warning нужно переделать компонент или заменить его на другой

            // <RadioBtn
            //   name="radio"
            //   id={i}
            //   key={i}
            //   viewValue={e.viewValue}
            //   groupValue={formik.values.status_id.value}
            //   radioValue={e.value}
            //   onChange={(value) =>
            //     formik.setFieldValue("status_id", {
            //       value: value,
            //       viewValue: e.viewValue,
            //     })
            //   }
            //   groupClasses={{
            //     group: "order-problem-radio",
            //     checked: "order-problem-radio-checked",
            //   }}
            // />
          )}
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
