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
import cn from "classnames";
import styles from "@modules/account/components/login-and-security/FormEditUserEmail.module.scss";
import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";

const FormEditUserEmail = (): any => {
  const router = useRouter();
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);
  const validationSchema = yup.object().shape({
    email: yup
      .string()
      .required("Email is a required field")
      .email("Email must be a valid email"),
  });

  function submit(values: any, actions: any) {
    dispatch(
      editEmailAction({
        data: values,

        success(res: any) {
          dispatch(userSetAction(res.data.user));
          router.push("/login-and-security");

          dispatch(
            setAlertAction({
              variant: "success",
              message: "You have successfully modified your account!",
            })
          );
        },

        error(err: any) {
          actions.setErrors(err);
        },

        complete() {
          actions.setSubmitting(false);
        },
      })
    );
  }

  if (!user) {
    return null;
  }

  const initialValues = {
    email: user.email,
  };

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
                <div className="px-10 px-md-0">
                  <p
                    className={cn("form-info", styles.currentEmailText, "mb-0")}
                  >
                    Current email address: <b>{user.email}</b>
                  </p>

                  <p className="form-info">
                    Enter the new email address you would like to associate with
                    your account below. We will send a One Time Password (OTP)
                    to that address.
                  </p>

                  <RBForm.Group
                    controlId={"EditUserEmail"}
                    className={cn("row", StylesLoginAndSecurity.formContainer)}
                  >
                    <div
                      className={
                        "col-12 col-md-6 col-lg-6 mb-10 mb-md-0 d-flex align-items-center justify-content-md-end justify-content-lg-start"
                      }
                    >
                      <Label>New Email Address</Label>
                    </div>

                    <div className={"col-12 col-md-6 col-lg-6"}>
                      <Input
                        type="text"
                        name="email"
                        value={values.email}
                        onChange={handleChange}
                        disabled={isSubmitting}
                        isInvalid={!!touched.email && !!errors.email}
                        isValid={touched.email && !errors.email}
                      />
                      <Feedback className="position-absolute" type="invalid">
                        {touched.email && errors.email}
                      </Feedback>
                    </div>
                  </RBForm.Group>
                </div>
              </InnerPage>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};

export default FormEditUserEmail;
