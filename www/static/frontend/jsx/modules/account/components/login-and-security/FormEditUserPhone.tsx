import { NavLink, useHistory } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import React from "react";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { editPhoneAction } from "@client/jsx/redux/actions/account-actions/LoginAndSecurityActions";
import { userSetAction } from "@client/jsx/redux/actions/account-actions/UserActions";

const FormEditUserPhone = (): any => {
  const history = useHistory();
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const initialValues = {
    phone: user.phone,
  };

  const validationSchema = yup.object().shape({
    phone: yup.string().required("Name is a required field"),
  });

  function submit(values, actions) {
    dispatch(
      editPhoneAction({
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
        Change your email address
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
                  Current email address: <b>{user.email}</b>
                  <br />
                  Enter the new email address you would like to associate with
                  your account below. We will send a One Time Password (OTP) to
                  that address.
                </p>

                <RBForm.Group controlId="EditUserPhone" className={"row"}>
                  <div
                    className={
                      "col-12 col-md-6 col-lg-6 text-md-end text-lg-start"
                    }
                  >
                    <RBForm.Label className={"form-input-label mb-1 mb-md-0"}>
                      Change your email address
                    </RBForm.Label>
                  </div>

                  <div className={"col-12 col-md-6 col-lg-6"}>
                    <RBForm.Control
                      type="text"
                      name="name"
                      value={values.phone}
                      onChange={handleChange}
                      className={"form-input"}
                      isInvalid={!!touched.phone && !!errors.phone}
                      isValid={touched.phone && !errors.phone}
                    />

                    <RBForm.Control.Feedback type="invalid">
                      {errors.phone}
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

export default FormEditUserPhone;
