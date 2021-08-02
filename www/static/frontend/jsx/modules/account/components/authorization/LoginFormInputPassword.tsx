import React from "react";
import { Formik, Form } from "formik";
import { Form as RBForm, OverlayTrigger, Tooltip } from "react-bootstrap";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faQuestionCircle } from "@fortawesome/free-solid-svg-icons";
import { loginAction } from "../../../../redux/actions/account-actions/AutorizationActions";
import { userSetAction } from "../../../../redux/actions/account-actions/UserActions";
import { useDispatch } from "react-redux";
import * as yup from "yup";
import { useHistory } from "react-router-dom";

const LoginFormInputPassword = function (props) {
  const history = useHistory();
  const dispatch = useDispatch();
  const inputRef = React.createRef();

  React.useEffect(() => {
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-ignore
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
      .min(6, "Password must be at least 6 characters")
      .max(32, "Password must be at most 32 characters"),
    rememberMe: yup.bool(),
  });

  function submit(values, actions) {
    dispatch(
      loginAction({
        form: {
          login: props.userLogin,
          password: values.password,
          remember_me: values.rememberMe,
        },

        success(res) {
          dispatch(userSetAction(res));
          history.push(appData.routes["account:index"]);
        },

        error(err) {
          actions.setErrors({ password: err.password[0] });
        },

        complete() {
          actions.setSubmitting(false);
        },
      })
    );
  }

  return (
    <>
      <p className={"auth-form-info d-flex justify-content-between mt-3 mb-3"}>
        <span>{props.userLogin}</span>

        <a href="#" onClick={props.goToInputLogin} className="common-link">
          Change
        </a>
      </p>

      <Formik
        initialValues={initialState}
        validationSchema={validationSchema}
        onSubmit={submit}
        ref={React.useRef()}
      >
        {({ isSubmitting, handleChange, values, touched, errors }) => {
          return (
            <Form>
              <RBForm.Group controlId="LoginFormPassword">
                <RBForm.Label className="d-flex justify-content-between align-items-center">
                  <span className={"form-input-label"}>Password</span>

                  <a href="#" className="common-link auth-form-info">
                    Forgot your password?
                  </a>
                </RBForm.Label>

                <RBForm.Control
                  ref={inputRef}
                  type="password"
                  name="password"
                  value={values.password}
                  onChange={handleChange}
                  className={"form-input form-input__password"}
                  isInvalid={errors.password}
                />

                <RBForm.Control.Feedback type="invalid">
                  {errors.password}
                </RBForm.Control.Feedback>
              </RBForm.Group>

              <button
                type="submit"
                className="form-button login-form_submit-button"
                disabled={isSubmitting}
              >
                sign-in
              </button>

              <RBForm.Group className={"mb-0"}>
                <input
                  name="rememberMe"
                  onChange={handleChange}
                  id="rememberMe"
                  className="form-checkbox"
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
                          className={"ml-1"}
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
