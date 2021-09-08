import React from "react";
import { Form, Formik } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import { resetPasswordAction } from "@client/jsx/redux/actions/account-actions/ResetPasswordActions";
import { useDispatch } from "react-redux";
import { route } from "@client/jsx/utils/AppData";
import { useHistory } from "react-router-dom";

interface PropsInterface {
  resetPasswordToken: string;
  goToLoginInput: () => void;
}

const ChangePasswordForm: React.FC<any> = function (props: PropsInterface) {
  const { resetPasswordToken, goToLoginInput } = props;
  const dispatch = useDispatch();
  const history = useHistory();
  const initialState = {
    password: "",
    confirmPassword: "",
  };

  const validationSchema = yup.object().shape({
    password: yup
      .string()
      .required("Password is a required field")
      .min(6, "Password must be at least 6 characters")
      .max(32, "Password must be at most 32 characters"),
    confirmPassword: yup
      .string()
      .min(6, "Password must be at least 6 characters")
      .max(32, "Password must be at most 32 characters")
      .oneOf([yup.ref("password"), null], "Passwords must match"),
  });

  function submit(values, actions): void {
    dispatch(
      resetPasswordAction({
        form: {
          password: values.password,
          resetPasswordToken,
        },

        success: function () {
          history.push(route("account:login"));
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
      ref={React.useRef()}
    >
      {({ isSubmitting, handleChange, values, errors }) => {
        return (
          <Form>
            <div className="px-12 px-sm-0">
              <h1 className="account-form-header">Change your password man</h1>

              <p className="auth-form-info mb-12 mb-md-14 mb-lg-20">
                We'll ask for this password whenever you Sign-In.
              </p>

              <RBForm.Group controlId="ChangePasswordPassword">
                <RBForm.Label className="form-input-label d-flex justify-content-between align-items-center">
                  Password
                </RBForm.Label>

                <RBForm.Control
                  type="password"
                  name="password"
                  value={values.password}
                  onChange={handleChange}
                  className={"form-input"}
                  isInvalid={!!errors.confirmPassword}
                  placeholder={"At least 6 characters"}
                />

                {!errors.confirmPassword && (
                  <RBForm.Text className={"auth-form-info_input-caption"}>
                    {"Password must be at least 6 characters"}
                  </RBForm.Text>
                )}

                <RBForm.Control.Feedback type="invalid">
                  {errors.confirmPassword}
                </RBForm.Control.Feedback>
              </RBForm.Group>

              <RBForm.Group controlId="ChangePasswordConfirmPassword">
                <RBForm.Label className="form-input-label d-flex justify-content-between align-items-center">
                  Re-Enter password
                </RBForm.Label>

                <RBForm.Control
                  type="password"
                  name="confirmPassword"
                  value={values.confirmPassword}
                  onChange={handleChange}
                  className={"form-input"}
                  isInvalid={!!errors.confirmPassword}
                />

                <RBForm.Control.Feedback type="invalid">
                  {errors.confirmPassword}
                </RBForm.Control.Feedback>
              </RBForm.Group>
            </div>

            <button
              type="submit"
              className="form-button mt-4"
              disabled={isSubmitting}
            >
              Submit
            </button>
          </Form>
        );
      }}
    </Formik>
  );
};

export default ChangePasswordForm;
