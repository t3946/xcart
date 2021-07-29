import React from "react";
import { Formik, Form, Field, ErrorMessage } from "formik";
import * as yup from "yup";
import classnames from "classnames";
import {
  checkUserLoginAction,
  loginAction,
} from "../../../../redux/actions/account-actions/AutorizationActions";
import { useDispatch, useSelector } from "react-redux";
import { NavLink, Redirect, useHistory } from "react-router-dom";
import { StoreDto } from "@s3stores-mail/ts/types";
import { Form as RBForm } from "react-bootstrap";
import { userSetAction } from "../../../../redux/actions/account-actions/UserActions";

const LoginForm: React.FC<any> = (props: any) => {
  const history = useHistory();
  const INPUT_LOGIN_MODE = 0;
  const INPUT_PASSWORD_MODE = 1;
  const [mode, setMode] = React.useState(INPUT_LOGIN_MODE);
  const [userLogin, setUserLogin] = React.useState("");
  const [showHelpInfo, setShowHelpInfo] = React.useState(false);
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);

  function loginInputTemplate(handleChange, values, touched, errors) {
    const Form = RBForm;

    if (mode === INPUT_LOGIN_MODE) {
      return (
        <Form.Group controlId="LoginFormLogin">
          <Form.Label className={"form-input-label"}>Email or mobile phone number</Form.Label>

          <Form.Control
            type="text"
            name="login"
            value={values.login}
            onChange={handleChange}
            className={"form-input"}
            isInvalid={errors.login}
          />

          <Form.Control.Feedback type="invalid">
            {errors.login}
          </Form.Control.Feedback>
        </Form.Group>
      );
    }
  }

  function passwordInputTemplate(handleChange, values, touched, errors) {
    const Form = RBForm;

    if (mode === INPUT_PASSWORD_MODE) {
      return (
        <Form.Group controlId="LoginFormPassword">
          <Form.Label className="d-flex justify-content-between align-items-center">
            <span className={"form-input-label"}>Password</span>

            <a href="#" className="common-link auth-form-info">
              Forgot your password?
            </a>
          </Form.Label>

          <Form.Control
            type="password"
            name="password"
            value={values.password}
            onChange={handleChange}
            className={"form-input form-input__password"}
            isInvalid={errors.password}
          />

          <Form.Control.Feedback type="invalid">
            {errors.password}
          </Form.Control.Feedback>
        </Form.Group>
      );
    }
  }

  function buttonTemplate() {
    const text = mode === INPUT_LOGIN_MODE ? "continue" : "sign-in";

    return (
      <button type="submit" className="form-button login-form_submit-button">
        {text}
      </button>
    );
  }

  function submit(values, actions) {
    if (mode === INPUT_LOGIN_MODE) {
      dispatch(
        checkUserLoginAction({
          form: { login: values.login },

          success() {
            setMode(INPUT_PASSWORD_MODE);
            setUserLogin(values.login);
          },

          error(err) {
            actions.setErrors({ login: err.login[0] });
          },
        })
      );
    } else {
      dispatch(
        loginAction({
          form: { login: userLogin, password: values.password },

          success(res) {
            dispatch(userSetAction(res));
            history.push(appData.routes["account:index"]);
          },

          error(err) {
            actions.setErrors({ password: err.password[0] });
          },
        })
      );
    }
  }

  function getInitValues() {
    if (mode === INPUT_LOGIN_MODE) {
      return {
        login: userLogin,
      };
    } else {
      return {
        password: "",
      };
    }
  }

  function getValidationSchema() {
    if (mode === INPUT_LOGIN_MODE) {
      return yup.object().shape({
        login: yup.string().required("Login is a required field"),
      });
    } else {
      return yup.object().shape({
        password: yup
          .string()
          .required("Password is a required field")
          .min(6, "Password must be at least 6 characters")
          .max(32, "Password must be at most 32 characters"),
      });
    }
  }

  return (
    <div className="account-login-form account_auth-form">
      <h1 className="account-form-header">Sign-In</h1>
      {user && <Redirect to="/account/" />}

      <Formik
        initialValues={getInitValues()}
        validationSchema={getValidationSchema()}
        onSubmit={submit}
        ref={React.useRef()}
      >
        {({ isSubmitting, handleChange, values, touched, errors }) => {
          return (
            <Form>
              {loginInputTemplate(handleChange, values, touched, errors)}

              {mode === INPUT_PASSWORD_MODE && (
                <p
                  className={
                    "auth-form-info d-flex justify-content-between mt-3 mb-3"
                  }
                >
                  <span>{userLogin}</span>

                  <a
                    href="#"
                    onClick={() => setMode(INPUT_LOGIN_MODE)}
                    className="common-link"
                  >
                    Change
                  </a>
                </p>
              )}

              {passwordInputTemplate(handleChange, values, touched, errors)}

              <p className={"auth-form-info"}>
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

              <p className={"auth-form-info"}>
                <a
                  href="#"
                  onClick={(e) => {
                    e.preventDefault();
                    setShowHelpInfo(!showHelpInfo);
                  }}
                  className={classnames("link-arrow common-link", {
                    "link-arrow__to-top": showHelpInfo,
                  })}
                >
                  Need help?
                </a>
                {showHelpInfo && (
                  <div>
                    <a href="#" className="common-link">
                      Forgot your password?
                    </a>
                    <br />
                    <a href="#" className="common-link">
                      Other issues with Sign-In
                    </a>
                  </div>
                )}
              </p>

              {buttonTemplate()}
            </Form>
          );
        }}
      </Formik>

      <div className="form-divider form-divider__with-content login-form_divider">
        <span className="form-divider-text">New to S3 Stores?</span>
      </div>

      <NavLink
        to="/account/register"
        exact={true}
        className="form-button form-button__outline common-link"
      >
        Create your account
      </NavLink>
    </div>
  );
};

export default LoginForm;
