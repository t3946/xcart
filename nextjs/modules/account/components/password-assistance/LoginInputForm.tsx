import React from "react";
import * as yup from "yup";
import { Form, Formik } from "formik";
import { Form as RBForm } from "react-bootstrap";
import { useDispatch } from "react-redux";
import { sendOneTimePasswordAction } from "@redux/actions/account-actions/ResetPasswordActions";

const LoginInputForm: React.FC<any> = function (props) {
  const firstInputRef = React.createRef<HTMLInputElement>();
  const dispatch = useDispatch();

  React.useEffect(() => {
    firstInputRef.current.focus();
  });

  const validationSchema = yup.object().shape({
    login: yup.string().required("Login is a required field"),
  });

  const initialState = {
    login: "",
  };

  function submit(values, actions) {
    dispatch(
      sendOneTimePasswordAction({
        form: values,

        success(res) {
          props.oneTimePasswordChanged(res.one_time_password);
          props.goToOTPInput(values.login);
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
    <Formik
      initialValues={initialState}
      validationSchema={validationSchema}
      onSubmit={submit}
    >
      {({ isSubmitting, handleChange, values, errors }) => {
        return (
          <Form>
            <div className="px-12 px-sm-0">
              <h1 className="account-form-header">Password Assistance</h1>

              <p className={"auth-form-info"}>
                Enter the email address or mobile phone number associated with
                your S3 Stores account.
              </p>

              <RBForm.Group controlId="LoginFormPassword">
                <RBForm.Label className="form-input-label">
                  Email or mobile phone number
                </RBForm.Label>

                <RBForm.Control
                  ref={firstInputRef}
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
            </div>
            <button
              type="submit"
              className="form-button mt-4"
              disabled={isSubmitting}
            >
              Continue
            </button>
          </Form>
        );
      }}
    </Formik>
  );
};

export default LoginInputForm;
