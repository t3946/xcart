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
import Link from "next/link";

import StylesLoginForm from "@modules/account/components/authorization/LoginForm.module.scss";
import Styles from "@modules/account/components/password-assistance/PasswordAssistance.module.scss";

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
              <h1 className="account-form-header mb-16 mb-sm-4 mb-lg-3 text-center text-sm-start">
                Password Assistance
              </h1>

              <p
                className={cn(
                  StylesLoginForm.text,
                  "auth-form-info",
                  "mb-18",
                  "mb-sm-12"
                )}
              >
                Enter the email address or mobile phone number associated with
                your S3 Stores account.
              </p>

              <RBForm.Group controlId="LoginFormPassword">
                <Label className={cn("mb-12 mb-sm-2 mb-lg-1 d-block")}>
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
              className={cn(StylesLoginForm.button, "form-button", "mt-4")}
              disabled={isSubmitting}
            >
              Continue
            </button>

            <div className="d-none d-sm-block d-lg-none mt-sm-16">
              <div className={cn("mb-sm-1", Styles.footerTitle)}>
                Has your email or mobile number changed?
              </div>
              <p className={Styles.footerText}>
                If you no longer use the email address associated with your S3
                Stores account, you may contact{" "}
                <Link href="#">
                  <a className="d-inline-block">Help Center</a>
                </Link>{" "}
                Service for help restoring access to your account.
              </p>
            </div>
          </Form>
        );
      }}
    </Formik>
  );
};

export default LoginInputForm;
