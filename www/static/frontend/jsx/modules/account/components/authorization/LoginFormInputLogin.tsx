import React from "react";
import { Formik, Form } from "formik";
import * as yup from "yup";
import { checkUserLoginAction } from "../../../../redux/actions/account-actions/AutorizationActions";
import { Form as RBForm } from "react-bootstrap";
import { NavLink } from "react-router-dom";
import classnames from "classnames";
import { useDispatch } from "react-redux";

const LoginFormInputLogin: React.FC<any> = (props: any) => {
  const dispatch = useDispatch();
  const validationSchema = yup.object().shape({
    login: yup.string().required("Login is a required field"),
  });
  const [showHelpInfo, setShowHelpInfo] = React.useState(false);
  const inputRef = React.createRef<HTMLInputElement>();

  const initialState = {
    login: props.lastSentForm.login || "",
  };

  React.useEffect(() => {
    inputRef.current.focus();
  });

  function submit(values, actions) {
    const form = { login: values.login };

    dispatch(
      checkUserLoginAction({
        form,

        success() {
          props.goToPasswordInput();
          props.setLastSentForm(form);
        },

        error(err) {
          actions.setErrors({ login: err.login[0] });
        },

        complete() {
          actions.setSubmitting(false);
        },
      })
    );
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
              <RBForm.Group controlId="LoginFormLogin">
                <RBForm.Label className={"form-input-label"}>
                  Email or mobile phone number
                </RBForm.Label>

                <RBForm.Control
                  ref={inputRef}
                  type="text"
                  name="login"
                  value={values.login}
                  onChange={handleChange}
                  className={"form-input"}
                  isInvalid={!!errors.login}
                />

                <RBForm.Control.Feedback type="invalid">
                  {errors.login}
                </RBForm.Control.Feedback>
              </RBForm.Group>

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

              <button
                type="submit"
                className="form-button login-form_submit-button"
                disabled={isSubmitting}
              >
                continue
              </button>
            </Form>
          );
        }}
      </Formik>

      <div className="form-divider form-divider__with-content auth-form_divider">
        <span className="form-divider-text">New to S3 Stores?</span>
      </div>

      <NavLink
        to="/account/register"
        exact={true}
        className="form-button form-button__outline common-link"
      >
        Create your account
      </NavLink>
    </>
  );
};

export default LoginFormInputLogin;
