import { NavLink, useHistory } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import React from "react";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { editNameAction } from "@client/jsx/redux/actions/account-actions/LoginAndSecurity";
import { userSetAction } from "@client/jsx/redux/actions/account-actions/UserActions";

const FormEditUserName = (): any => {
  const history = useHistory();
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const initialValues = {
    name: user.name,
  };

  const validationSchema = yup.object().shape({
    name: yup.string().required("Name is a required field"),
  });

  function submit(values, actions) {
    dispatch(
      editNameAction({
        form: values,

        success(res) {
          dispatch(userSetAction(res.user));
          history.push(route("account:login-and-security"));
          console.log(res);
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
        Change your name
      </h1>

      <Formik
        initialValues={initialValues}
        validationSchema={validationSchema}
        onSubmit={submit}
      >
        {function ({ isSubmitting, values, errors, touched, handleChange }) {
          console.log({
            isSubmitting,
            values,
            errors,
            touched,
            handleChange,
          });
          return (
            <Form>
              <div className="content-panel">
                <p className="form-info">
                  If you want to change the name associated with your S3 Stores
                  customer account, you may do so below. Be sure to click the{" "}
                  <b>Save Changes</b> button when you are done.
                </p>

                <RBForm.Group controlId="EditUserName" className={"row"}>
                  <div
                    className={
                      "col-12 col-md-6 col-lg-6 text-md-end text-lg-start"
                    }
                  >
                    <RBForm.Label className={"form-input-label mb-1 mb-md-0"}>
                      New Full name
                    </RBForm.Label>
                  </div>

                  <div className={"col-12 col-md-6 col-lg-6"}>
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

export default FormEditUserName;
