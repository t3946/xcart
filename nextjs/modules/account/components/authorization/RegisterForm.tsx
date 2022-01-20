import React from "react";
import { Formik, Form } from "formik";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import * as yup from "yup";
import { useRouter } from "next/router";
import Link from "next/link";
import { useDispatch } from "react-redux";
import { Form as RBForm } from "react-bootstrap";
import { registerAction } from "@redux/actions/account-actions/AutorizationActions";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import classNames from "classnames";
import { noSidebarClasses } from "@modules/account/ts/consts/no-sidebar-classes";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

import Styles from "@modules/account/components/authorization/LoginForm.module.scss";

const RegisterForm: React.FC<any> = () => {
  const router = useRouter();
  const routes = useSelectorAccount((e) => e.routes);
  const user = useSelectorAccount((e) => e.user);
  const dispatch = useDispatch();

  const initialValues = {
    name: "",
    email: "",
    password: "",
    password_confirm: "",
  };

  const validationSchema = yup.object().shape({
    name: yup.string().required("Name is a required field"),
    email: yup
      .string()
      .required("Email is a required field")
      .email("Email must be a valid email"),
    password: yup
      .string()
      .required("Password confirm required")
      .min(8, "Password must be at least 8 characters")
      .max(32, "Password must be at most 32 characters"),
    password_confirm: yup
      .string()
      .required("Password confirm required")
      .min(8, "Password must be at least 8 characters")
      .max(32, "Password must be at most 32 characters")
      .oneOf([yup.ref("password"), null], "Passwords must match"),
  });

  function submit(values: Record<any, any>, actions: Record<any, any>) {
    const { email, name, password } = values;

    dispatch(
      registerAction({
        data: {
          email,
          name,
          password,
        },

        success(res: any) {
          actions.setSubmitting(false);

          if (res.data.error) {
            actions.setErrors(res.data.error);
          } else {
            dispatch(userSetAction(res.data.user));
            router.push("/dashboard");
          }
        },
      })
    );
  }

  if (user) {
    router.push("/");
  }

  return (
    <div
      className={classNames(noSidebarClasses, "account-auth-form-container")}
    >
      <div
        className={classNames(
          "account-auth-form account_auth-form",
          Styles.authForm
        )}
      >
        <Formik
          initialValues={initialValues}
          validationSchema={validationSchema}
          onSubmit={submit}
        >
          {({ isSubmitting, values, errors, touched, handleChange }) => (
            <Form>
              <div className="px-12 px-sm-0">
                <h1 className="account-form-header mb-18 mb-sm-4 mb-lg-14 text-center text-sm-start">
                  Create Account
                </h1>

                <RBForm.Group
                  className="mb-14 mb-sm-18 mb-lg-12"
                  controlId="RegisterFormName"
                >
                  <Label className="mb-12 mb-sm-10 mb-lg-2 d-block">
                    Your Name
                  </Label>

                  <Input
                    name="name"
                    value={values.name}
                    onChange={handleChange}
                    isInvalid={!!touched.name && !!errors.name}
                    isValid={touched.name && !errors.name}
                  />

                  <Feedback type="invalid">
                    {!!touched.name && errors.name}
                  </Feedback>
                </RBForm.Group>

                <RBForm.Group
                  className="mb-14 mb-sm-18 mb-lg-12"
                  controlId="RegisterFormEmail"
                >
                  <Label className="mb-12 mb-sm-10 mb-lg-2 d-block">
                    Email
                  </Label>
                  <Input
                    name="email"
                    value={values.email}
                    onChange={handleChange}
                    isInvalid={!!touched.email && !!errors.email}
                    isValid={touched.email && !errors.email}
                  />
                  <Feedback type="invalid">
                    {!!touched.email && errors.email}
                  </Feedback>
                </RBForm.Group>

                <RBForm.Group
                  className="mb-14 mb-sm-12 mb-lg-10"
                  controlId="RegisterFormPassword"
                >
                  <Label className="mb-12 mb-sm-10 mb-lg-1 d-block">
                    Password
                  </Label>
                  <Input
                    type="password"
                    name="password"
                    value={values.password}
                    onChange={handleChange}
                    isInvalid={touched.password && !!errors.password}
                    isValid={touched.password && !errors.password}
                    placeholder={"At least 8 characters "}
                  />
                  <Feedback type="invalid">
                    {touched.password && errors.password}
                  </Feedback>

                  {!(touched.password && errors.password) && (
                    <RBForm.Text
                      className={classNames(
                        Styles.inputCaption,
                        "auth-form-info_input-caption",
                        "d-block",
                        "mt-md-10",
                        "mt-lg-1"
                      )}
                    >
                      {"Passwords must be at least 8 characters"}
                    </RBForm.Text>
                  )}
                </RBForm.Group>

                <RBForm.Group
                  className="mb-14 mb-sm-18 mb-lg-12"
                  controlId="RegisterForm"
                >
                  <Label className="mb-12 mb-sm-10 mb-lg-1 d-block">
                    Re-Enter password
                  </Label>
                  <Input
                    type="password"
                    name="password_confirm"
                    value={values.password_confirm}
                    onChange={handleChange}
                    isInvalid={
                      touched.password_confirm && !!errors.password_confirm
                    }
                    isValid={
                      touched.password_confirm && !errors.password_confirm
                    }
                  />
                  <Feedback type="invalid">
                    {touched.password_confirm && errors.password_confirm}
                  </Feedback>
                </RBForm.Group>
              </div>

              <button
                type="submit"
                className={classNames(
                  "form-button",
                  "login-form_submit-button",
                  Styles.button
                )}
                disabled={isSubmitting}
              >
                Create your account
              </button>

              <p className={"margin-0 auth-form-info px-12 px-sm-0"}>
                By continuing, you agree to S3 Stores Inc{" "}
                <a href="#" className="common-link">
                  Conditions of Use
                </a>{" "}
                and{" "}
                <a href="#" className="common-link">
                  Privacy Notice
                </a>
                .
              </p>

              <div className="d-sm-none d-lg-block">
                <div
                  className={classNames(
                    "form-divider",
                    "form-divider__with-content auth-form_divider",
                    "mt-4",
                    "mt-lg-10",
                    "mb-lg-14"
                  )}
                >
                  <span
                    className={classNames(
                      "form-divider-text",
                      Styles.dividerText
                    )}
                  >
                    Already have an account?
                  </span>
                </div>

                <Link href={routes["account:login"]}>
                  <a
                    className={classNames(
                      "form-button",
                      "form-button__outline",
                      "common-link",
                      "fw-bold",
                      Styles.button
                    )}
                  >
                    sign in
                  </a>
                </Link>
              </div>

              <div className="d-none d-sm-block d-lg-none">
                <div
                  className={classNames(
                    "form-divider auth-form_divider mb-0 mt-sm-16",
                    Styles.authForm__divider
                  )}
                />

                <p
                  className={
                    "auth-form-info register-form_already-have-account mt-sm-18"
                  }
                >
                  Already have an account?{" "}
                  <Link href={routes["account:login"]}>
                    <a className="common-link"> Sign-In</a>
                  </Link>
                </p>
              </div>
            </Form>
          )}
        </Formik>
      </div>
    </div>
  );
};

export default RegisterForm;
