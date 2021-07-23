import React from "react";
import { Formik, Form, Field, ErrorMessage } from "formik";
import * as yup from "yup";
import { FormInput } from "../shared/FormInput";
import classnames from "classnames";
import { loginAction } from "../../../../redux/actions/account-actions/AutorizationActions";
import { useDispatch } from "react-redux";
import { NavLink } from "react-router-dom";

const LoginForm: React.FC<any> = (props: any) => {
  const INPUT_LOGIN_MODE = 0;
  const INPUT_PASSWORD_MODE = 1;
  const [mode, setMode] = React.useState(INPUT_LOGIN_MODE);
  const [showHelpInfo, setShowHelpInfo] = React.useState(false);
  const dispatch = useDispatch();

  const initialValues = {
    login: "vendor@s3stores.com",
    password: "123qwe",
  };

  const validationSchema = yup.object().shape({
    login: yup.string().required("Login is a required field"),
    password: yup
      .string()
      .required("Password is a required field")
      .min(6, "Password must be at least 6 characters")
      .max(32, "Password must be at most 32 characters"),
  });

  const FormInputClasses = {
    group: ["login-from_input-group"],
    label: ["login-from_label"],
  };

  const formRef = React.useRef();

  function loginInputTemplate(handleChange, value) {
    if (mode === INPUT_LOGIN_MODE) {
      return (
        <React.Fragment>
          <FormInput
            label={"Email or mobile phone number"}
            name={"login"}
            handleChange={handleChange}
            classes={FormInputClasses}
            value={value}
          />
          <ErrorMessage
            className={"login-form-error"}
            name="name"
            component="div"
          />
          <a href="#" className={"login-form-info common-link"}>
            Forgot your email?
          </a>
        </React.Fragment>
      );
    }
  }

  function passwordInputTemplate(handleChange, value) {
    function backToLoginInput(e) {
      e.preventDefault();

      setMode(INPUT_LOGIN_MODE);
    }

    if (mode === INPUT_PASSWORD_MODE) {
      return (
        <React.Fragment>
          <p
            className={
              "login-form-info d-flex justify-content-between mt-3 mb-3"
            }
          >
            <span>{initialValues.login}</span>
            <a href="#" onClick={backToLoginInput} className="common-link">
              Change
            </a>
          </p>
          <FormInput
            label={"Password"}
            name={"password"}
            type={"password"}
            caption={"Passwords must be at least 6 characters"}
            handleChange={handleChange}
            classes={FormInputClasses}
            value={value}
          />
          <ErrorMessage
            className={"login-form-error"}
            name="password"
            component="div"
          />
        </React.Fragment>
      );
    }
  }

  function buttonTemplate() {
    const text = mode === INPUT_LOGIN_MODE ? "continue" : "sing in";
    const type = mode === INPUT_LOGIN_MODE ? "button" : "submit";

    function click(e) {
      if (mode === INPUT_LOGIN_MODE) {
        setMode(INPUT_PASSWORD_MODE);
        e.preventDefault();
      }
    }

    return (
      <button
        type={type}
        className="form-button login-form_submit-button"
        onClick={click}
      >
        {text}
      </button>
    );
  }

  function submit(values) {
    const data = {
      "LoginForm[login]": values["login"],
      "LoginForm[password]": values["password"],
    };

    dispatch(
      loginAction({
        form: { login: "vendor@s3stores.com", password: "123qwe" },
        callback: function () {
          console.log("callback");
        },
      })
    );

    // $.ajax({
    //   method: "POST",
    //   data,
    //   url: "/account/login/",
    //   success() {
    //     document.location.href = "/account/";
    //   },
    //   error(err) {
    //     console.log(err);
    //   },
    // });
  }

  return (
    <div className="account-login-form">
      <h1 className="account-form-header">Sign-In</h1>

      <Formik
        initialValues={initialValues}
        validationSchema={validationSchema}
        onSubmit={(e) => submit(e)}
        ref={formRef}
      >
        {({ isSubmitting, handleChange, values }) => (
          <Form>
            {loginInputTemplate(handleChange, values["login"])}

            {passwordInputTemplate(handleChange, values["password"])}

            <p className={"login-form-info"}>
              By continuing, you agree to S3 Stores Inc{" "}
              <a href="#" className="common-link">Conditions of Use</a> and{" "}
              <a href="#" className="common-link">Privacy Notice</a>.
            </p>

            <p className={"login-form-info"}>
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
                  <a href="#" className="common-link">Forgot your password?</a>
                  <br />
                  <a href="#" className="common-link">Other issues with Sign-In</a>
                </div>
              )}
            </p>

            {buttonTemplate()}
          </Form>
        )}
      </Formik>

      <div className="form-divider form-divider__with-content login-form_divider">
        <span className="form-divider-text">New to S3 Stores?</span>
      </div>

      <NavLink
        to="/account/register/"
        exact={true}
        className="form-button form-button__outline common-link"
      >
        Create your account
      </NavLink>
    </div>
  );
};

export default LoginForm;
