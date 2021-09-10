import { useHistory } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import React, { useContext } from "react";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { editNameAction } from "@client/jsx/redux/actions/account-actions/LoginAndSecurityActions";
import { userSetAction } from "@client/jsx/redux/actions/account-actions/UserActions";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import InnerPage from "@client/modules/account/components/shared/InnerPage";
import SubmitCancelButtonsGroup from "@client/modules/account/components/shared/SubmitCancelButtonsGroup";

const FormEditUserName = (): any => {
  const history = useHistory();
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const { showSnackbar } = useContext(SnackbarContext);
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
          showSnackbar({
            header: "Success",
            message: "You have successfully modified your account!",
            theme: "success",
          });
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
    <Formik
      initialValues={initialValues}
      validationSchema={validationSchema}
      onSubmit={submit}
    >
      {function ({ isSubmitting, values, errors, touched, handleChange }) {
        return (
          <Form>
            <InnerPage
              header={"Change your name"}
              bodyClasses={"content-panel"}
              footerClasses={"text-center text-lg-start"}
              footer={
                <SubmitCancelButtonsGroup
                  submitText={"save changes"}
                  disabled={isSubmitting}
                  buttonAdvancedClasses={"form-button__submit-and-cancel p-0"}
                  groupAdvancedClasses={
                    "d-md-flex justify-content-center justify-content-lg-start"
                  }
                  onCancel={() => {
                    history.push(route("account:login-and-security"));
                  }}
                />
              }
            >
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
                  <RBForm.Label
                    className={
                      "form-input-label mb-1 mb-md-0 d-md-flex align-items-center justify-content-end justify-content-lg-start"
                    }
                  >
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
            </InnerPage>
          </Form>
        );
      }}
    </Formik>
  );
};

export default FormEditUserName;
