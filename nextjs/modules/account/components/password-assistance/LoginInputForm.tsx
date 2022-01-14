import React from "react";
import * as yup from "yup";
import { Form, Formik, FormikHelpers } from "formik";
import { Form as RBForm } from "react-bootstrap";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import { useDispatch } from "react-redux";
import { sendOneTimePasswordAction } from "@redux/actions/account-actions/ResetPasswordActions";
import { AxiosResponse } from "axios";
import cn from "classnames";

import Styles from "@modules/account/components/password-assistance/LoginInputForm.module.scss";

const LoginInputForm: React.FC<any> = function (props) {
  const firstInputRef = React.createRef<HTMLInputElement>();
  const dispatch = useDispatch();

  React.useEffect(() => {
    firstInputRef.current.focus();
  });

  const validationSchema = yup.object().shape({
    login: yup.string().required("Login is a required field"),
  });

  const initialState = {
    login: "",
  };

  function submit(values: Record<any, any>, actions: FormikHelpers<any>): void {
    actions.setSubmitting(true);

    dispatch(
      sendOneTimePasswordAction({
        form: values,

        success(res: AxiosResponse) {
          props.oneTimePasswordChanged(res.data);
          props.goToOTPInput(values.login);
        },

        error(err: any) {
          actions.setErrors({ login: err.login });
        },

        complete() {
          actions.setSubmitting(false);
        },
      })
    );
  }

  return (
    <Formik
      initialValues={initialState}
      validationSchema={validationSchema}
      onSubmit={submit}
    >
      {({ isSubmitting, handleChange, values, errors, touched }) => {
        return (
          <Form>
            <div className="px-12 px-sm-0">
              <h1 className="account-form-header mb-lg-3">
                Password Assistance
              </h1>

              <p className={cn(Styles.text, "auth-form-info")}>
                Enter the email address or mobile phone number associated with
                your S3 Stores account.
              </p>

              <RBForm.Group controlId="LoginFormPassword">
                <Label className={cn("mb-lg-1 d-block")}>
                  Email or mobile phone number
                </Label>

                <Input
                  ref={firstInputRef}
                  type="text"
                  name="login"
                  value={values.login}
                  onChange={handleChange}
                  isInvalid={touched.login && !!errors.login}
                />

                <Feedback type="invalid">
                  {!!touched.login && errors.login}
                </Feedback>
              </RBForm.Group>
            </div>
            <button
              type="submit"
              className={cn(Styles.text, "form-button", "mt-4")}
              disabled={isSubmitting}
            >
              Continue
            </button>
          </Form>
        );
      }}
    </Formik>
  );
};

export default LoginInputForm;
