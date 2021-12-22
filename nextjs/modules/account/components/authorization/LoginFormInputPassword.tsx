import React from "react";
import { Formik, Form } from "formik";
import { Form as RBForm, OverlayTrigger, Tooltip } from "react-bootstrap";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
//todo: remove this dependencies
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faQuestionCircle } from "@fortawesome/free-solid-svg-icons/faQuestionCircle";
import * as yup from "yup";
import FingerprintJS from "@fingerprintjs/fingerprintjs";
import { useDispatch } from "react-redux";
import { loginAction } from "@redux/actions/account-actions/AutorizationActions";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import { useRouter } from "next/router";
import Link from "next/link";
import cn from "classnames";
import Styles from "@modules/account/components/authorization/LoginFormInputPassword.module.scss";

interface IProps {
  login: string;
  goToInputLogin: () => void;
  goToOTPInput: () => void;
}

const LoginFormInputPassword = function (props: IProps): any {
  const dispatch = useDispatch();
  const router = useRouter();
  const inputRef = React.createRef<HTMLInputElement>();
  const { goToInputLogin, goToOTPInput, login } = props;

  React.useEffect(() => {
    inputRef.current.focus();
  }, []);

  const initialState = {
    password: "",
    rememberMe: false,
  };
  const validationSchema = yup.object().shape({
    password: yup
      .string()
      .required("Password is a required field")
      .min(8, "Password must be at least 8 characters")
      .max(32, "Password must be at most 32 characters"),
    rememberMe: yup.bool(),
  });

  async function generateFp() {
    // Initialize an agent at application startup.
    const fpPromise = FingerprintJS.load();

    return await (async () => {
      // Get the visitor identifier when you need it.
      const fp = await fpPromise;
      const result = await fp.get();

      // This is the visitor identifier:
      return result.visitorId;
    })();
  }

  function submit(values, actions) {
    //function submit must be synchronous because need wrap async part
    (async function wrapAsyncFunc() {
      actions.setSubmitting(true);

      dispatch(
        loginAction({
          form: {
            login,
            password: values.password,
            remember_me: values.rememberMe,
            fingerprint: await generateFp(),
          },

          success(res: any) {
            if (res.user) {
              dispatch(userSetAction(res.user));
              router.push("/");
            } else {
              actions.setSubmitting(false);
              goToOTPInput();
            }
          },

          error(err: any) {
            actions.setSubmitting(false);
            actions.setErrors({ password: err.password[0] });
          },
        })
      );
    })();
  }

  return (
    <>
      <Formik
        initialValues={initialState}
        validationSchema={validationSchema}
        onSubmit={submit}
      >
        {(formikProps) => {
          const { isSubmitting, handleChange, values, errors, touched } =
            formikProps;

          return (
            <Form>
              <div className="px-12 px-sm-0">
                <p
                  className={
                    "auth-form-info d-flex justify-content-between mt-3 mb-3"
                  }
                >
                  <span>{login}</span>

                  <a href="#" onClick={goToInputLogin} className="common-link">
                    Change
                  </a>
                </p>

                <RBForm.Group controlId="LoginFormPassword">
                  <Label className="d-flex justify-content-between align-items-center">
                    <span className={"form-input-label"}>Password</span>

                    <Link
                      href={
                        "/login-and-security/two-step-verification/password-assistance"
                      }
                    >
                      <a className={cn(Styles.authFormInfo, Styles.commonLink)}>
                        Forgot your password?
                      </a>
                    </Link>
                  </Label>

                  <Input
                    ref={inputRef}
                    type="password"
                    name="password"
                    value={values.password}
                    onChange={handleChange}
                    isInvalid={!!errors.password && !!touched.password}
                  />
                  <Feedback type="invalid">
                    {!!touched.password && errors.password}
                  </Feedback>
                </RBForm.Group>
              </div>

              <button
                type="submit"
                className="form-button login-form_submit-button"
                disabled={isSubmitting}
              >
                sign-in
              </button>

              <RBForm.Group className={"mb-0 px-12 px-sm-0"}>
                <input
                  name="rememberMe"
                  onChange={handleChange}
                  id="rememberMe"
                  className="form-checkbox d-none"
                  type="checkbox"
                  value={values.rememberMe}
                />

                <RBForm.Label
                  className={
                    "checkbox-label mb-0 align-items-center d-flex form-label"
                  }
                  htmlFor={"rememberMe"}
                >
                  <div className="auth-form-info">
                    Keep me signed in.{" "}
                    <OverlayTrigger
                      placement="top"
                      overlay={
                        <Tooltip
                          id="tooltip-details"
                          className={
                            "common-tooltip common-tooltip__login-form"
                          }
                        >
                          <h2 className="common-tooltip-header">
                            "Keep Me Signed In" Checkbox
                          </h2>

                          <p className={"text-align--left auth-form-info"}>
                            Choosing "Keep me signed in" reduces the number of
                            times you're asked to Sign-In on this device.
                          </p>

                          <p className={"text-align--left auth-form-info mb-0"}>
                            To keep your account secure, use this option only on
                            your personal devices.
                          </p>
                        </Tooltip>
                      }
                    >
                      <span className={"common-link"}>
                        Details
                        <FontAwesomeIcon
                          className={"ms-1"}
                          icon={faQuestionCircle}
                        />
                      </span>
                    </OverlayTrigger>
                  </div>
                </RBForm.Label>
              </RBForm.Group>
            </Form>
          );
        }}
      </Formik>
    </>
  );
};

export default LoginFormInputPassword;
