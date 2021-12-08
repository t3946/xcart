import React from "react";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import Link from "next/link";
import { useRouter } from "next/router";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

const LoginFormInputOTP = function (props: Record<any, any>): any {
  const routes = useSelectorAccount((e) => e.routes);
  const router = useRouter();
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
        router.push(routes["account:dashboard"]);
      },

      error(err) {
        actions.setErrors({ otp: err.otp[0] });
      },
    });
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
              <p className={"auth-form-info"}>
                For added security, please enter the One Time Password (OTP)
                generation by your by Authenticator App
              </p>

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
            </div>

            <button
              type="submit"
              className="form-button mt-4 mb-3"
              disabled={isSubmitting}
            >
              sign-in
            </button>

            <div className="px-12 px-sm-0">
              <Link href={routes["account:two-step-verification-recovery"]}>
                <a className={"auth-form-info"}>
                  Two-step Verification account recovery
                </a>
              </Link>
            </div>
          </Form>
        );
      }}
    </Formik>
  );
};

export default LoginFormInputOTP;
