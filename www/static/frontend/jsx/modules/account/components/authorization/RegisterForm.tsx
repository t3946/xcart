import React from "react";
import { Formik, Form } from "formik";
import * as yup from "yup";
import { NavLink, useHistory } from "react-router-dom";
import { StoreDto } from "@s3stores-mail/ts/types";
import { useDispatch, useSelector } from "react-redux";
import { Form as RBForm } from "react-bootstrap";
import { registerAction } from "@client/jsx/redux/actions/account-actions/AutorizationActions";
import { userSetAction } from "@client/jsx/redux/actions/account-actions/UserActions";
import { route } from "@client/jsx/utils/AppData";

const RegisterForm: React.FC<any> = () => {
  const user = useSelector((e: StoreDto) => e.user);

  user && useHistory().push(route("account:index"));

  const initialValues = {
    name: "",
    email: "",
    password: "",
    password_confirm: "",
  };
  const formRef = React.useRef();
  const dispatch = useDispatch();
  const history = useHistory();

  const validationSchema = yup.object().shape({
    name: yup.string().required("Name is a required field"),
    email: yup
      .string()
      .required("Email is a required field")
      .email("Email must be a valid email"),
    password: yup
      .string()
      .required("Password confirm required")
      .min(6, "Password must be at least 6 characters")
      .max(32, "Password must be at most 32 characters"),
    password_confirm: yup
      .string()
      .required("Password confirm required")
      .min(6, "Password must be at least 6 characters")
      .max(32, "Password must be at most 32 characters")
      .oneOf([yup.ref("password"), null], "Passwords must match"),
  });

  function submit(values, actions) {
    dispatch(
      registerAction({
        form: values,

        success(res) {
          dispatch(userSetAction(res));
          history.push(route("account:index"));
        },

        error(err) {
          actions.setErrors(err);
        },

        complete() {
          actions.setSubmitting(false);
        },
      })
    );
  }

  return (
    <div className="account-auth-form account_auth-form">
      <h1 className="account-form-header">Create account</h1>

      <Formik
        initialValues={initialValues}
        validationSchema={validationSchema}
        onSubmit={submit}
        ref={formRef}
      >
        {({ isSubmitting, values, errors, touched, handleChange }) => (
          <Form>
            <RBForm.Group controlId="RegisterFormName">
              <RBForm.Label className={"form-input-label"}>
                Your Name
              </RBForm.Label>

              <RBForm.Control
                type="text"
                name="name"
                value={values.name}
                onChange={handleChange}
                className={"form-input"}
                isInvalid={!!touched.name && !!errors.name}
                isValid={touched.name && !errors.name}
              />

              <RBForm.Control.Feedback type="invalid">
                {errors.name}
              </RBForm.Control.Feedback>
            </RBForm.Group>

            <RBForm.Group controlId="RegisterFormEmail">
              <RBForm.Label className={"form-input-label"}>Email</RBForm.Label>

              <RBForm.Control
                type="text"
                name="email"
                value={values.email}
                onChange={handleChange}
                className={"form-input"}
                isInvalid={!!touched.email && !!errors.email}
                isValid={touched.email && !errors.email}
              />

              <RBForm.Control.Feedback type="invalid">
                {errors.email}
              </RBForm.Control.Feedback>
            </RBForm.Group>

            <RBForm.Group controlId="RegisterFormPassword">
              <RBForm.Label className={"form-input-label"}>
                Password
              </RBForm.Label>

              <RBForm.Control
                type="password"
                name="password"
                value={values.password}
                onChange={handleChange}
                className={"form-input form-input__password"}
                isInvalid={touched.password && !!errors.password}
                isValid={touched.password && !errors.password}
                placeholder={"At least 6 characters "}
              />

              <RBForm.Control.Feedback type="invalid">
                {errors.password}
              </RBForm.Control.Feedback>

              {!(touched.password && errors.password) && (
                <RBForm.Text className={"auth-form-info_input-caption"}>
                  {"Passwords must be at least 6 characters"}
                </RBForm.Text>
              )}
            </RBForm.Group>

            <RBForm.Group controlId="RegisterForm">
              <RBForm.Label className={"form-input-label"}>
                Re-Enter password
              </RBForm.Label>

              <RBForm.Control
                type="password"
                name="password_confirm"
                value={values.password_confirm}
                onChange={handleChange}
                className={"form-input form-input__password"}
                isInvalid={
                  touched.password_confirm && !!errors.password_confirm
                }
                isValid={touched.password_confirm && !errors.password_confirm}
              />

              <RBForm.Control.Feedback type="invalid">
                {errors.password_confirm}
              </RBForm.Control.Feedback>
            </RBForm.Group>

            <button
              type="submit"
              className="form-button login-form_submit-button"
              disabled={isSubmitting}
            >
              Create your account
            </button>

            <p className={"margin-0 auth-form-info"}>
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

            <div className="d-sm-none">
              <div className="form-divider form-divider__with-content auth-form_divider">
                <span className="form-divider-text">
                  Already have an account?
                </span>
              </div>

              <NavLink
                to={route("account:login")}
                exact={true}
                className="form-button form-button__outline common-link"
              >
                sign in
              </NavLink>
            </div>

            <div className="d-none d-sm-block">
              <div className="form-divider auth-form_divider mb-0" />

              <p
                className={"auth-form-info register-form_already-have-account"}
              >
                Already have an account?{" "}
                <NavLink
                  to={route("account:login")}
                  className="common-link"
                  exact={true}
                >
                  Sign-In
                </NavLink>
              </p>
            </div>
          </Form>
        )}
      </Formik>
    </div>
  );
};

export default RegisterForm;
