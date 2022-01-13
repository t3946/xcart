import React from "react";
import { Formik, Form, FormikHelpers } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import Link from "next/link";
import { useRouter } from "next/router";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { AxiosResponse } from "axios";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import { useDispatch } from "react-redux";

const LoginFormInputOTP = function (props: Record<any, any>): any {
  const { login, password } = props;
  const routes = useSelectorAccount((e) => e.routes);
  const router = useRouter();
  const inputRef = React.createRef<HTMLInputElement>();
  const dispatch = useDispatch();

  React.useEffect(() => {
    inputRef.current.focus();
  });

  const initialState = {
    code: "",
    rememberBrowser: false,
  };
  const validationSchema = yup.object().shape({
    code: yup.string().required("OTP is a required field"),
    rememberBrowser: yup.bool(),
  });

  async function submit(values: Record<any, any>, actions: FormikHelpers<any>) {
    props.submit({
      actions,
      form: { ...values, password, login },

      success(res: AxiosResponse) {
        if (res.data.error) {
          actions.setErrors(res.data.error);
          return;
        }

        if (res.data.user) {
          dispatch(userSetAction(res.data.user));
          router.push("/dashboard");
          return;
        }
      },
    });
  }

  return (
    <Formik
      initialValues={initialState}
      validationSchema={validationSchema}
      onSubmit={submit}
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
                  <span className={"form-input-label"}>Enter OTP</span>
                </RBForm.Label>

                <RBForm.Control
                  ref={inputRef}
                  type="text"
                  name="code"
                  value={values.code}
                  onChange={handleChange}
                  className={"form-input"}
                  isInvalid={!!errors.code}
                />

                <RBForm.Control.Feedback type="invalid">
                  {errors.code}
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
