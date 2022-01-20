import React from "react";
import { Form, Formik } from "formik";
import { Form as RBForm } from "react-bootstrap";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import * as yup from "yup";
import ChevronDownLight from "@modules/icon/components/font-awesome/chevron-down/Light";
import classnames from "classnames";
import {
  sendOneTimePasswordAction,
  verifyOneTimePasswordAction,
} from "@redux/actions/account-actions/ResetPasswordActions";
import { useDispatch } from "react-redux";
import ResendOtpButton from "@modules/account/components/password-assistance/ResendOtpButton";

import StylesLoginInput from "@modules/account/components/authorization/LoginForm.module.scss";
import Styles from "@modules/account/components/password-assistance/OneTimePasswordInputForm.module.scss";

const OneTimePasswordInputForm: React.FC<any> = function (props) {
  const {
    login,
    oneTimePassword,
    oneTimePasswordChanged,
    goToLoginInput,
    goToResetPassword,
  } = props;
  const [showMoreHelp, setShowMoreHelp] = React.useState(false);
  const [showNewCodeSent, setShowNewCodeSent] = React.useState(false);
  const dispatch = useDispatch();

  const validationSchema = yup.object().shape({
    otp: yup.string().required("OTP is a required field"),
  });

  const initialState = {
    otp: "",
  };

  function submit(values: Record<any, any>, actions: Record<any, any>) {
    const form = {
      otp: values.otp,
      login: props.login,
    };

    dispatch(
      verifyOneTimePasswordAction({
        form,

        success(res: any) {
          const { errors, otp, resetPasswordToken } = res.data;

          if (otp) {
            oneTimePasswordChanged(otp);
          }

          if (errors) {
            if (errors.otp === "outdated") {
              alert("Session outdated. Please try again!");
              goToLoginInput();
            } else {
              actions.setErrors(errors);
            }

            return;
          }

          goToResetPassword(resetPasswordToken);
          setShowNewCodeSent(false);
        },

        complete() {
          actions.setSubmitting(false);
        },
      })
    );
  }

  const classes = {
    attempts: [
      "reset-password-attempts",
      {
        "reset-password-attempts__last": oneTimePassword.left_attempts < 2,
      },
    ],
    newCodeSent: [
      "auth-form-info_input-caption auth-form-info__theme-success",
      showNewCodeSent ? "d-block" : "d-none",
    ],
  };

  function decLeftResendTime() {
    oneTimePassword.left_is_new_time = oneTimePassword.left_is_new_time - 1;
    oneTimePasswordChanged(oneTimePassword);
  }

  function sendOneTimePassword(complete: any) {
    dispatch(
      sendOneTimePasswordAction({
        form: {
          login,
        },

        success(res: Record<any, any>) {
          props.oneTimePasswordChanged(res.data);
          setShowNewCodeSent(true);
        },

        complete,
      })
    );
  }

  if (showNewCodeSent && oneTimePassword.left_is_new_time === 0) {
    setShowNewCodeSent(false);
  }

  return (
    <Formik
      initialValues={initialState}
      validationSchema={validationSchema}
      onSubmit={submit}
    >
      {({ isSubmitting, handleChange, values, errors, touched }) => {
        return (
          <Form>
            <div className="px-12 px-sm-0">
              <h1 className="account-form-header mb-16 mb-sm-4 mb-lg-16 text-center text-sm-start">
                Authentication required
              </h1>

              <p
                className={classnames(
                  Styles.text,
                  "mb-18",
                  "mb-sm-12",
                  "mb-lg-10",
                  "auth-form-info"
                )}
              >
                For your security, we need to authenticate your request. We've
                sent a One Time Password (OTP) to the email{" "}
                <b onClick={goToLoginInput} className={"cursor__default"}>
                  {login}
                </b>
                . Please enter it below.
              </p>

              <RBForm.Group controlId="LoginFormPassword">
                <Label className="d-flex justify-content-between align-items-center mb-12 mb-sm-2 mb-lg-2">
                  <span>Enter OTP</span>
                  <span className={classnames(classes.attempts)}>
                    Left {oneTimePassword.left_attempts} attempts
                  </span>
                </Label>

                <Input
                  type="text"
                  name="otp"
                  value={values.otp}
                  onChange={handleChange}
                  className={classnames("form-input", Styles.input)}
                  isInvalid={touched.otp && !!errors.otp}
                  maxLength={6}
                />

                <RBForm.Text className={classnames(classes.newCodeSent)}>
                  {"A new code has been sent to your email."}
                </RBForm.Text>

                <Feedback type="invalid">
                  {!!touched.otp && errors.otp}
                </Feedback>
              </RBForm.Group>
            </div>

            <button
              type="submit"
              className={classnames(
                "form-button",
                "mt-4",
                StylesLoginInput.button
              )}
              disabled={isSubmitting || oneTimePassword.left_attempts === 0}
            >
              Continue
            </button>

            <div className="px-12 px-sm-0">
              <div className={"mt-18 mt-sm-16 text-center auth-form-info"}>
                <ResendOtpButton
                  leftResendTime={oneTimePassword.left_is_new_time}
                  decLeftResendTime={decLeftResendTime}
                  sendOneTimePassword={sendOneTimePassword}
                />
              </div>

              <div className={"mt-10 mt-sm-3 auth-form-info"}>
                <span
                  className="common-link"
                  onClick={() => setShowMoreHelp(!showMoreHelp)}
                >
                  I Need More Help
                  <ChevronDownLight
                    className={classnames("spoiler-arrow ms-2", {
                      "spoiler-arrow__active": showMoreHelp,
                    })}
                  />
                </span>

                <ul
                  className={classnames(
                    showMoreHelp ? "d-block" : "d-none",
                    "mb-0",
                    "mt-lg-1"
                  )}
                >
                  <li>
                    If you've already tried to reset your password, but haven't
                    received an email from S3 stores, check your Junk or Spam
                    folder.
                  </li>
                  <li>
                    If you can't access your email, try resetting that first
                    through your email provider.
                  </li>
                  <li>
                    If you've recently updated your password, your old password
                    could still be saved in your browser. Try clearing your
                    browser history and re-typing your password.
                  </li>
                  <li>
                    If you need more password help, call us at 1-800-929-2431
                  </li>
                </ul>
              </div>
            </div>
          </Form>
        );
      }}
    </Formik>
  );
};

export default OneTimePasswordInputForm;
