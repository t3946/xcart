import React from "react";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import { Link, useHistory } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";

const LoginFormInputOTP = function (props: Record<any, any>): any {
  const history = useHistory();
  const inputRef = React.createRef<HTMLInputElement>();

  React.useEffect(() => {
    inputRef.current.focus();
  });

  const initialState = {
    otp: "",
    rememberBrowser: false,
  };
  const validationSchema = yup.object().shape({
    otp: yup.string().required("OTP is a required field"),
    rememberBrowser: yup.bool(),
  });

  async function submit(values, actions) {
    const form = props.lastSentForm;
    form.otp = values.otp;
    form.rememberBrowser = values.rememberBrowser;

    props.submit({
      actions,
      form,

      success() {
        history.push(route("account:dashboard"));
      },

      error(err) {
        actions.setErrors({ otp: err.otp[0] });
      },
    });
  }

  return (
    <>
      <p className={"auth-form-info"}>
        For added security, please enter the One Time Password (OTP) generation
        by your by Authenticator App
      </p>

      <Formik
        initialValues={initialState}
        validationSchema={validationSchema}
        onSubmit={submit}
        ref={React.useRef()}
      >
        {({ isSubmitting, handleChange, values, errors }) => {
          return (
            <Form>
              <RBForm.Group controlId="LoginFormPassword">
                <RBForm.Label className="d-flex justify-content-between align-items-center">
                  <span className={"form-input-label"}>Enter OTP </span>
                </RBForm.Label>

                <RBForm.Control
                  ref={inputRef}
                  type="text"
                  name="otp"
                  value={values.otp}
                  onChange={handleChange}
                  className={"form-input"}
                  isInvalid={!!errors.otp}
                />

                <RBForm.Control.Feedback type="invalid">
                  {errors.otp}
                </RBForm.Control.Feedback>
              </RBForm.Group>

              <RBForm.Group className={"mb-0 mt-3"}>
                <input
                  name="rememberBrowser"
                  onChange={handleChange}
                  id="rememberBrowser"
                  className="form-checkbox"
                  type="checkbox"
                  value={values.rememberBrowser}
                />

                <RBForm.Label
                  className={
                    "checkbox-label mb-0 align-items-center d-flex form-label"
                  }
                  htmlFor={"rememberBrowser"}
                >
                  <div className="auth-form-info">
                    Don’t require OTP on this browser
                  </div>
                </RBForm.Label>
              </RBForm.Group>

              <button
                type="submit"
                className="form-button mt-4 mb-3"
                disabled={isSubmitting}
              >
                sign-in
              </button>

              <Link
                to={route("account:two-step-verification-recovery")}
                className={"auth-form-info"}
              >
                Two-step Verification account recovery
              </Link>
            </Form>
          );
        }}
      </Formik>
    </>
  );
};

export default LoginFormInputOTP;
