import React from "react";
import { Formik, Form } from "formik";
import { Form as RBForm, OverlayTrigger, Tooltip } from "react-bootstrap";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faQuestionCircle } from "@fortawesome/free-solid-svg-icons/faQuestionCircle";
import * as yup from "yup";
import { useHistory, Link } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import FingerprintJS from "@fingerprintjs/fingerprintjs";

const LoginFormInputPassword = function (props: Record<any, any>): any {
  const history = useHistory();
  const inputRef = React.createRef<HTMLInputElement>();

  React.useEffect(() => {
    inputRef.current.focus();
  });

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

  async function submit(values, actions) {
    props.submit({
      actions,
      form: {
        login: props.lastSentForm.login,
        password: values.password,
        remember_me: values.rememberMe,
        fingerprint: await generateFp(),
      },

      success(res) {
        if (!res.user) {
          props.goToOTPInput();
        } else {
          history.push(route("account:index"));
        }
      },

      error(err) {
        actions.setErrors({ password: err.password[0] });
      },
    });
  }

  return (
    <>
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
                <p
                  className={
                    "auth-form-info d-flex justify-content-between mt-3 mb-3"
                  }
                >
                  <span>{props.lastSentForm.login}</span>

                  <a
                    href="#"
                    onClick={props.goToInputLogin}
                    className="common-link"
                  >
                    Change
                  </a>
                </p>

                <RBForm.Group controlId="LoginFormPassword">
                  <RBForm.Label className="d-flex justify-content-between align-items-center">
                    <span className={"form-input-label"}>Password</span>

                    <Link
                      to={route(
                        "account:two-step-verification-recovery-password-assistance"
                      )}
                      className={"common-link auth-form-info"}
                    >
                      Forgot your password?
                    </Link>
                  </RBForm.Label>

                  <RBForm.Control
                    ref={inputRef}
                    type="password"
                    name="password"
                    value={values.password}
                    onChange={handleChange}
                    className={"form-input"}
                    isInvalid={!!errors.password}
                  />

                  <RBForm.Control.Feedback type="invalid">
                    {errors.password}
                  </RBForm.Control.Feedback>
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
