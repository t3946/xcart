import { useHistory } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import React from "react";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { changePasswordAction } from "@client/jsx/redux/actions/account-actions/LoginAndSecurityActions";
import { userSetAction } from "@client/jsx/redux/actions/account-actions/UserActions";

const FormChangePassword = (): any => {
  const history = useHistory();
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const initialValues = {
    old_password: "123qwe",
    new_password: "123123",
    confirm_password: "123123",
  };

  const validationSchema = yup.object().shape({
    old_password: yup.string().required("Old Password required"),
    new_password: yup
      .string()
      .required("New password required")
      .min(6, "Password must be at least 6 characters")
      .max(32, "Password must be at most 32 characters"),
    confirm_password: yup
      .string()
      .required("Password confirm required")
      .oneOf([yup.ref("new_password"), null], "Passwords must match"),
  });

  function submit(values, actions) {
    dispatch(
      changePasswordAction({
        form: values,

        success(res) {
          dispatch(userSetAction(res.user));
          history.push(route("account:login-and-security"));
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
    <div>
      <h1 className="account-page_header text-center text-lg-start">
        Change password
      </h1>

      <Formik
        initialValues={initialValues}
        validationSchema={validationSchema}
        onSubmit={submit}
      >
        {function ({ isSubmitting, values, errors, touched, handleChange }) {
          return (
            <Form>
              <div className="content-panel">
                <p className="form-info">
                  Use the form below to change the password for your S3 Stores
                  account
                </p>

                <RBForm.Control
                  className={"d-none"}
                  type="text"
                  name="login"
                  value={user.email}
                />

                <RBForm.Group controlId="ChangePassword" className="row mb-20">
                  <div
                    className={
                      "col-12 col-md-6 col-lg-6 text-md-end text-lg-start"
                    }
                  >
                    <RBForm.Label className={"form-input-label mb-1 mb-md-0"}>
                      Current password
                    </RBForm.Label>
                  </div>

                  <div className={"col-12 col-md-6 col-lg-6"}>
                    <RBForm.Control
                      type="password"
                      name="old_password"
                      value={values.old_password}
                      onChange={handleChange}
                      className={"form-input"}
                      isInvalid={
                        !!touched.old_password && !!errors.old_password
                      }
                      isValid={touched.old_password && !errors.old_password}
                      autoComplete={"current-password"}
                    />

                    <RBForm.Control.Feedback type="invalid">
                      {errors.old_password}
                    </RBForm.Control.Feedback>
                  </div>
                </RBForm.Group>

                <RBForm.Group
                  controlId="ChangePasswordNew"
                  className="row mb-20"
                >
                  <div
                    className={
                      "col-12 col-md-6 col-lg-6 text-md-end text-lg-start"
                    }
                  >
                    <RBForm.Label className={"form-input-label mb-1 mb-md-0"}>
                      New password
                    </RBForm.Label>
                  </div>

                  <div className={"col-12 col-md-6 col-lg-6"}>
                    <RBForm.Control
                      type="password"
                      name="new_password"
                      value={values.new_password}
                      onChange={handleChange}
                      className={"form-input"}
                      isInvalid={
                        !!touched.new_password && !!errors.new_password
                      }
                      isValid={touched.new_password && !errors.new_password}
                      autoComplete="new-password"
                    />

                    <RBForm.Control.Feedback type="invalid">
                      {errors.new_password}
                    </RBForm.Control.Feedback>
                  </div>
                </RBForm.Group>

                <RBForm.Group controlId="ChangePasswordConfirm" className="row">
                  <div
                    className={
                      "col-12 col-md-6 col-lg-6 text-md-end text-lg-start"
                    }
                  >
                    <RBForm.Label className={"form-input-label mb-1 mb-md-0"}>
                      Reenter new password
                    </RBForm.Label>
                  </div>

                  <div className={"col-12 col-md-6 col-lg-6"}>
                    <RBForm.Control
                      type="password"
                      name="confirm_password"
                      value={values.confirm_password}
                      onChange={handleChange}
                      className={"form-input"}
                      isInvalid={
                        !!touched.confirm_password && !!errors.confirm_password
                      }
                      isValid={
                        touched.confirm_password && !errors.confirm_password
                      }
                      autoComplete="new-password"
                    />

                    <RBForm.Control.Feedback type="invalid">
                      {errors.confirm_password}
                    </RBForm.Control.Feedback>
                  </div>
                </RBForm.Group>
              </div>

              <div className="account-page_footer text-center text-lg-start">
                <button
                  className={
                    "admin-form-control form-button form-button__wide w-md-auto d-inline-block"
                  }
                  disabled={isSubmitting}
                >
                  save changes
                </button>
              </div>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};

export default FormChangePassword;
