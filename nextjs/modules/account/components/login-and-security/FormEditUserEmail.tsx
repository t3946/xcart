import { useRouter } from "next/router";
import React from "react";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import { useDispatch } from "react-redux";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import {
  editEmailAction,
  setAlertAction,
} from "@redux/actions/account-actions/LoginAndSecurityActions";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import InnerPage from "@modules/account/components/shared/InnerPage";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";

import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";

const FormEditUserEmail = (): any => {
  const router = useRouter();
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);
  const initialValues = {
    email: user.email,
  };

  const validationSchema = yup.object().shape({
    email: yup
      .string()
      .required("Email is a required field")
      .email("Email must be a valid email"),
  });

  function submit(values, actions) {
    dispatch(
      editEmailAction({
        form: values,

        success(res) {
          dispatch(userSetAction(res.user));
          router.push("/login-and-security");

          dispatch(
            setAlertAction({
              variant: "success",
              message: "You have successfully modified your account!",
            })
          );
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
      <Formik
        initialValues={initialValues}
        validationSchema={validationSchema}
        onSubmit={submit}
      >
        {function ({ isSubmitting, values, errors, touched, handleChange }) {
          return (
            <Form>
              <InnerPage
                header={"Change your email address"}
                headerClasses={"text-center text-lg-start"}
                bodyClasses={["content-panel", StylesLoginAndSecurity.pageBody]}
                footer={
                  <SubmitCancelButtonsGroup
                    submitText={"save changes"}
                    disabled={isSubmitting}
                    buttonAdvancedClasses={"form-button__submit-and-cancel p-0"}
                    groupAdvancedClasses={
                      "d-md-flex justify-content-center justify-content-lg-start"
                    }
                    onCancel={() => {
                      router.push("/login-and-security");
                    }}
                  />
                }
              >
                <p className="form-info">
                  Current email address: <b>{user.email}</b>
                  <br />
                  Enter the new email address you would like to associate with
                  your account below. We will send a One Time Password (OTP) to
                  that address.
                </p>

                <RBForm.Group controlId="EditUserEmail" className={"row"}>
                  <div
                    className={
                      "col-12 col-md-6 col-lg-6 text-md-end text-lg-start"
                    }
                  >
                    <Label className={"mb-1 mb-md-0"}>
                      Change your email address
                    </Label>
                  </div>

                  <div className={"col-12 col-md-6 col-lg-6"}>
                    <Input
                      type="text"
                      name="email"
                      value={values.email}
                      onChange={handleChange}
                      isInvalid={!!touched.email && !!errors.email}
                      isValid={touched.email && !errors.email}
                    />
                    <Feedback type="invalid">
                      {touched.email && errors.email}
                    </Feedback>
                  </div>
                </RBForm.Group>
              </InnerPage>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};

export default FormEditUserEmail;
