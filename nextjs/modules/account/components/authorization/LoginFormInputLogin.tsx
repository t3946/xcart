import React from "react";
import { Formik, Form } from "formik";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import * as yup from "yup";
import { checkUserLoginAction } from "@redux/actions/account-actions/AutorizationActions";
import { Form as RBForm } from "react-bootstrap";
import Link from "next/link";
import cn from "classnames";
import { useDispatch } from "react-redux";

import Styles from "@modules/account/components/authorization/LoginForm.module.scss";

const LoginFormInputLogin: React.FC<any> = (props: any) => {
  const { setLogin } = props;
  const dispatch = useDispatch();
  const validationSchema = yup.object().shape({
    login: yup
      .string()
      .required("Login is a required field")
      .test(
        "loginFormat",
        "Enter valid email or phone number",
        async function (value) {
          return (
            !!value &&
            (value.search(/[^@]+?@[^@]+/) !== -1 ||
              value.search(/\+\d{11}/) !== -1)
          );
        }
      ),
  });
  const [showHelpInfo, setShowHelpInfo] = React.useState(false);
  const inputRef = React.createRef<HTMLInputElement>();

  const initialState = {
    login: props.lastSentForm.login || "",
  };

  React.useEffect(() => {
    inputRef.current?.focus();
  });

  function submit(values: any, actions: any) {
    const form = { login: values.login };

    dispatch(
      checkUserLoginAction({
        data: form,

        success(res: any) {
          const error = res.data.error;

          actions.setSubmitting(false);

          if (error) {
            actions.setErrors({ login: error });
          } else {
            setLogin(form.login);
            props.goToPasswordInput();
            props.setLastSentForm(form);
          }
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
      >
        {({ isSubmitting, handleChange, values, errors, touched }) => {
          return (
            <Form>
              <RBForm.Group
                controlId="LoginFormLogin"
                className={"px-12 px-sm-0"}
              >
                <Label className={"d-block mb-12 mb-sm-12 mb-lg-2"}>
                  Email or mobile phone number
                </Label>
                <Input
                  ref={inputRef}
                  name="login"
                  value={values.login}
                  onChange={handleChange}
                  isInvalid={!!errors.login && !!touched.login}
                />
                <Feedback type="invalid">
                  {!!touched.login && errors.login}
                </Feedback>
              </RBForm.Group>

              <button
                type="submit"
                className={cn(
                  "form-button",
                  "login-form_submit-button",
                  Styles.button,
                  "mb-lg-10"
                )}
                disabled={isSubmitting}
              >
                continue
              </button>

              <div className="px-12 px-sm-0">
                <p className={"auth-form-info"}>
                  By continuing, you agree to S3 Stores Inc{" "}
                  <a href="/terms-of-use" className="common-link">
                    Conditions of Use
                  </a>{" "}
                  and{" "}
                  <a href="/privacy-policy" className="common-link">
                    Privacy Notice
                  </a>
                  .
                </p>

                <p className={cn("auth-form-info", "mb-0")}>
                  <a
                    href="#"
                    onClick={(e) => {
                      e.preventDefault();
                      setShowHelpInfo(!showHelpInfo);
                    }}
                    className={cn("link-arrow common-link", {
                      "link-arrow__to-top": showHelpInfo,
                    })}
                  >
                    Need help?
                  </a>
                  {showHelpInfo && (
                    <div className={"mt-1"}>
                      <Link href={"/password-assistance"}>
                        <a className="common-link">Forgot your password?</a>
                      </Link>
                      <br />
                      <a href="#" className="common-link d-none">
                        Other issues with Sign-In
                      </a>
                    </div>
                  )}
                </p>
              </div>
            </Form>
          );
        }}
      </Formik>

      <div
        className={cn(
          "form-divider",
          "form-divider__with-content",
          "mt-4",
          "mb-3",
          "mt-sm-14",
          "mb-sm-16",
          "mt-lg-10",
          "mb-lg-16"
        )}
      >
        <span className={cn("form-divider-text", Styles.dividerText)}>
          New to S3 Stores?
        </span>
      </div>

      <Link href={"/register"}>
        <a
          className={cn(
            "form-button",
            "form-button__outline",
            "common-link",
            "p-0",
            Styles.button
          )}
        >
          Create your account
        </a>
      </Link>
    </>
  );
};

export default LoginFormInputLogin;
