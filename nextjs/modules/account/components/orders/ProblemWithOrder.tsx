import React, { useContext, useEffect, useState } from "react";
import cn from "classnames";
import FormSelect from "@modules/ui/forms/Select";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import { ApiService } from "@modules/shared/services/api.service";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import { useFormik } from "formik";
import * as Yup from "yup";
import { useRouter } from "next/router";
import { problemsWithOrderSelectValue } from "@modules/account/ts/consts/order-actions-select.const";
import RadioQuestion from "modules/account/components/orders/Decision/LTLFreightShipment/RadioQuestion";

import StylesOrderActions from "@modules/account/components/orders/OrderActions.module.scss";
import Styles from "@modules/account/components/orders/ProblemWithOrder.module.scss";

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
    <div className={cn("order-product-list-body-inner p-lg-0")}>
      <div
        className={cn(
          "page-label",
          "problem-with-order-label",
          "text-md-start",
          StylesOrderActions.title
        )}
      >
        Problem with order
      </div>

      <form onSubmit={formik.handleSubmit}>
        <div className="d-none d-md-block">
          <p className="what-went-wrong">What went wrong?</p>
          <FormSelect
            classes={{ group: "order-product-select-errors" }}
            value={formik.values.status_id}
            items={statuses}
            onClick={(value) => formik.setFieldValue("status_id", value)}
            id={"problem-with-order-select"}
          />
        </div>

        <div className="order-problems-radios">
          <RadioQuestion
            classes={{ container: "border-0", card: Styles.radioCard }}
            question={{
              label: "What went wrong?",
              name: "status_id",
              radios: statuses,
            }}
            checkedValues={formik.values}
            disabled={formik.isSubmitting}
            onChange={(e) => {
              formik.setFieldValue("status_id", e);
            }}
          />
        </div>

        <Input
          as="textarea"
          name={"problem_text"}
          onChange={formik.handleChange}
          disabled={formik.isSubmitting}
          placeholder="Describe your issue"
          value={formik.values.problem_text}
          isValid={formik.touched.problem_text && !formik.errors.problem_text}
          isInvalid={
            !!formik.touched.problem_text && !!formik.errors.problem_text
          }
          className={cn(
            StylesOrderActions.problemTextArea,
            StylesOrderActions.form__problemTextArea
          )}
        />
        <Feedback type="invalid">
          {!!formik.touched.problem_text && formik.errors.problem_text}
        </Feedback>
        <button
          disabled={loading}
          type="submit"
          className={cn(
            "form-button",
            "fw-bold",
            StylesOrderActions.button,
            "mx-md-auto",
            "mx-lg-0",
            "mb-4"
          )}
        >
          send
        </button>
      </form>
    </div>
  );
};
