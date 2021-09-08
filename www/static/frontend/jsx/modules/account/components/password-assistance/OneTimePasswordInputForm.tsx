import React from "react";
import { Form, Formik } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import ChevronDownLight from "@client/jsx/modules/icon/components/font-awesome/chevron-down/Light";
import classnames from "classnames";
import { verifyOneTimePasswordAction } from "@client/jsx/redux/actions/account-actions/ResetPasswordActions";
import { useDispatch } from "react-redux";
import ResendOtpButton from "@client/modules/account/components/password-assistance/ResendOtpButton";
import { sendEmailAction } from "@client/jsx/redux/actions/account-actions/ResetPasswordActions";

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
    one_time_password: yup.string(),
  });

  const initialState = {
    one_time_password: "",
  };

  function submit(values, actions) {
    const form = {
      one_time_password: values.one_time_password,
      login: props.login,
    };

    dispatch(
      verifyOneTimePasswordAction({
        form,

        success(res) {
          goToResetPassword(res.resetPasswordToken);
          setShowNewCodeSent(false);
        },

        error(err) {
          if (err.errors.otp === "outdated") {
            alert("Session outdated. Please try again!");
            goToLoginInput();
            return;
          }

          actions.setErrors({ login: err.errors.one_time_password[0] });
          oneTimePasswordChanged(err.one_time_password);
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

  function sendOneTimePassword(complete) {
    dispatch(
      sendEmailAction({
        form: {
          login,
        },

        success(res) {
          props.oneTimePasswordChanged(res.one_time_password);
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
      ref={React.useRef()}
    >
      {({ isSubmitting, handleChange, values, errors }) => {
        return (
          <Form>
            <div className="px-12 px-sm-0">
              <h1 className="account-form-header">Authentication required</h1>

              <p className={"auth-form-info"}>
                For your security, we need to authenticate your request. We've
                sent a One Time Password (OTP) to the email{" "}
                <b onClick={goToLoginInput} className={"cursor__default"}>
                  {login}
                </b>
                . Please enter it below.
              </p>

              <RBForm.Group controlId="LoginFormPassword">
                <RBForm.Label className="form-input-label d-flex justify-content-between align-items-center">
                  <span>Enter OTP</span>
                  <span className={classnames(classes.attempts)}>
                    Left {oneTimePassword.left_attempts} attempts
                  </span>
                </RBForm.Label>

                <RBForm.Control
                  type="text"
                  name="one_time_password"
                  value={values.one_time_password}
                  onChange={handleChange}
                  className={"form-input"}
                  isInvalid={!!errors.one_time_password}
                />

                <RBForm.Text className={classnames(classes.newCodeSent)}>
                  {"A new code has been sent to your email."}
                </RBForm.Text>

                <RBForm.Control.Feedback type="invalid">
                  {errors.one_time_password}
                </RBForm.Control.Feedback>
              </RBForm.Group>
            </div>

            <button
              type="submit"
              className="form-button mt-4"
              disabled={isSubmitting || oneTimePassword.left_attempts === 0}
            >
              Continue
            </button>

            <div className="px-12 px-sm-0">
              <div className={"mt-20 text-center auth-form-info"}>
                <ResendOtpButton
                  leftResendTime={oneTimePassword.left_is_new_time}
                  decLeftResendTime={decLeftResendTime}
                  sendOneTimePassword={sendOneTimePassword}
                />
              </div>

              <div className={"mt-3 auth-form-info"}>
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
                    "mb-0"
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
