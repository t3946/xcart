import React from "react";
import { Form, Formik } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import { resetPasswordAction } from "@redux/actions/account-actions/ResetPasswordActions";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";
import cn from "classnames";

import StylesLoginForm from "@modules/account/components/authorization/LoginForm.module.scss";
import Styles from "@modules/account/components/password-assistance/ChangePasswordForm.module.scss";

interface IProps {
  login: string;
  resetPasswordToken: string;
  goToLoginInput: () => void;
}

const ChangePasswordForm: React.FC<any> = function (props: IProps) {
  const { resetPasswordToken, goToLoginInput, login } = props;
  const dispatch = useDispatch();
  const router = useRouter();
  const initialState = {
    password: "",
    confirmPassword: "",
  };

  const validationSchema = yup.object().shape({
    password: yup
      .string()
      .required("Password is a required field")
      .min(8, "Password must be at least 8 characters")
      .max(32, "Password must be at most 32 characters"),
    confirmPassword: yup
      .string()
      .required("Password Confirm is a required field")
      .min(8, "Password must be at least 8 characters")
      .max(32, "Password must be at most 32 characters")
      .oneOf([yup.ref("password"), null], "Passwords must match"),
  });

  function submit(values, actions): void {
    dispatch(
      resetPasswordAction({
        form: {
          login: login,
          password: values.password,
          resetPasswordToken,
        },

        success: function () {
          router.push("/login");
        },

        error(err) {
          if (err.otp === "outdated") {
            alert("Session outdated. Please try again!");
            goToLoginInput();
            return;
          }

          actions.setErrors({ password: err.password[0] });
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
      {({ touched, isSubmitting, handleChange, values, errors }) => {
        return (
          <Form>
            <div className="px-12 px-sm-0">
              <h1 className="account-form-header text-center text-sm-start mb-18 mb-sm-4 mb-lg-16">
                Create New Password
              </h1>

              <p
                className={cn(
                  Styles.caption,
                  "auth-form-info mb-sm-12 mb-lg-20"
                )}
              >
                We'll ask for this password whenever you Sign-In.
              </p>

              <RBForm.Group
                className="mb-10 mb-sm-12 mb-lg-2"
                controlId="ChangePasswordPassword"
              >
                <RBForm.Label className="d-block form-input-label mb-12 mb-lg-1">
                  <span className="d-none d-sm-inline">New </span>Password
                </RBForm.Label>

                <RBForm.Control
                  type="password"
                  name="password"
                  value={values.password}
                  onChange={handleChange}
                  className={"form-input"}
                  isInvalid={!!touched.password && !!errors.password}
                  isValid={touched.password && !errors.password}
                  placeholder={"At least 8 characters"}
                />

                {(!errors.password || !touched.password) && (
                  <RBForm.Text
                    className={cn(
                      "auth-form-info_input-caption",
                      "form-group-text",
                      StylesLoginForm.inputCaption,
                      "mt-2",
                      "mt-lg-10",
                      "d-block"
                    )}
                  >
                    {"Passwords must be at least 8 characters"}
                  </RBForm.Text>
                )}

                <RBForm.Control.Feedback type="invalid">
                  {errors.password}
                </RBForm.Control.Feedback>
              </RBForm.Group>

              <RBForm.Group controlId="ChangePasswordConfirmPassword">
                <RBForm.Label className="form-input-label d-flex justify-content-between align-items-center mb-12 mb-lg-2">
                  Re-Enter password
                </RBForm.Label>

                <RBForm.Control
                  type="password"
                  name="confirmPassword"
                  value={values.confirmPassword}
                  onChange={handleChange}
                  className={"form-input"}
                  isInvalid={
                    !!touched.confirmPassword && !!errors.confirmPassword
                  }
                  isValid={touched.confirmPassword && !errors.confirmPassword}
                />

                <RBForm.Control.Feedback type="invalid">
                  {errors.confirmPassword}
                </RBForm.Control.Feedback>
              </RBForm.Group>
            </div>

            <button
              type="submit"
              className={cn(
                "form-button",
                "d-block",
                "mt-4",
                StylesLoginForm.button
              )}
              disabled={isSubmitting}
            >
              Save <span className="d-none d-lg-inline">changes</span> and
              Sign-In
            </button>
          </Form>
        );
      }}
    </Formik>
  );
};

export default ChangePasswordForm;
