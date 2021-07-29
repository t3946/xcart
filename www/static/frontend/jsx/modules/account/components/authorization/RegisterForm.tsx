import React from "react";
import { Formik, Form, ErrorMessage } from "formik";
import * as yup from "yup";
import { FormInput } from "../shared/FormInput";
import $ from "jquery";
import {NavLink, Redirect} from "react-router-dom";
import { StoreDto } from "@s3stores-mail/ts/types";
import { useSelector } from "react-redux";

const RegisterForm: React.FC<any> = (props: any) => {
  const user = useSelector((e: StoreDto) => e.user);
  const initialValues = {
    name: "",
    email: "",
    password: "",
    password_confirm: "",
  };

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
      .max(32, "Password must be at most 32 characters"),
  });

  const FormInputClasses = {
    group: ["login-from_input-group"],
    label: ["login-from_label"],
  };

  const formRef = React.useRef();

  function submit() {
    const data = {
      "RegistrationForm[name]": formRef.current.base["name"].value,
      "RegistrationForm[email]": formRef.current.base["email"].value,
      "RegistrationForm[password]": formRef.current.base["password"].value,
      "RegistrationForm[password_confirm]":
        formRef.current.base["password_confirm"].value,
    };

    $.ajax({
      method: "POST",
      data,
      url: "/account/register/",
      success() {
        document.location.href = "/account/";
      },
      error(err) {
        console.log(err);
      },
    });
  }

  return (
    <div className="account-login-form">
      {user && <Redirect to="/account/" />}
      <h1 className="account-form-header">Create account</h1>

      <Formik
        initialValues={initialValues}
        validationSchema={validationSchema}
        onSubmit={submit}
        ref={formRef}
      >
        {({ isSubmitting, handleChange }) => (
          <Form>
            <FormInput
              label={"Your name"}
              name={"name"}
              handleChange={handleChange}
              classes={FormInputClasses}
              autocomplete={"off"}
            />
            <ErrorMessage
              className={"login-form-error"}
              name="name"
              component="div"
            />

            <FormInput
              label={"Email"}
              name={"email"}
              handleChange={handleChange}
              classes={FormInputClasses}
              autocomplete={"off"}
            />
            <ErrorMessage
              className={"login-form-error"}
              name="email"
              component="div"
            />

            <FormInput
              label={"Password"}
              name={"password"}
              type={"password"}
              caption={"Passwords must be at least 6 characters"}
              handleChange={handleChange}
              classes={FormInputClasses}
              autocomplete={"off"}
            />
            <ErrorMessage
              className={"login-form-error"}
              name="password"
              component="div"
            />

            <FormInput
              label={"Re-Enter password"}
              name={"password_confirm"}
              type={"password"}
              handleChange={handleChange}
              classes={FormInputClasses}
              autocomplete={"off"}
            />
            <ErrorMessage
              className={"login-form-error"}
              name="password_confirm"
              component="div"
            />

            <button
              type="submit"
              className="form-button login-form_submit-button"
              disabled={isSubmitting}
            >
              Create your account
            </button>

            <p className={"margin-0 login-form-info"}>
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

            <div className="form-divider login-form_divider" />

            <p className={"margin-0 login-form-info"}>
              Already have an account?{" "}
              <NavLink
                to="/account/login/"
                className="common-link"
                exact={true}
              >Sign-In</NavLink>
            </p>
          </Form>
        )}
      </Formik>
    </div>
  );
};

export default RegisterForm;
