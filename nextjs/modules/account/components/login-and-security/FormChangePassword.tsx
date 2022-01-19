import { useRouter } from "next/router";
import React from "react";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import { useDispatch } from "react-redux";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import {
  changePasswordAction,
  setAlertAction,
} from "@redux/actions/account-actions/LoginAndSecurityActions";
import InnerPage from "@modules/account/components/shared/InnerPage";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";

import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";
import Styles from "@modules/account/components/login-and-security/FormChangePassword.module.scss";

const FormChangePassword = (): any => {
  const router = useRouter();
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);
  const initialValues = {
    oldPassword: "",
    newPassword: "",
    confirmPassword: "",
  };

  const validationSchema = yup.object().shape({
    oldPassword: yup.string().required("Old Password required"),
    newPassword: yup
      .string()
      .required("New password required")
      .min(8, "Password must be at least 8 characters")
      .max(32, "Password must be at most 32 characters"),
    confirmPassword: yup
      .string()
      .required("Password confirm required")
      .oneOf([yup.ref("newPassword"), null], "Passwords must match"),
  });

  function submit(values: any, actions: any) {
    dispatch(
      changePasswordAction({
        data: values,

        success() {
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
                header={"Change password"}
                headerClasses={"text-center text-lg-start"}
                bodyClasses={["content-panel", StylesLoginAndSecurity.pageBody]}
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
                      router.push("/login-and-security");
                    }}
                  />
                }
              >
                <div className="px-10 px-md-0">
                  <p className={Styles.formInfo}>
                    Use the form below to change the password for your S3 Stores
                    account
                  </p>

                  <Input
                    type="hidden"
                    disabled={isSubmitting}
                    name="login"
                    value={user.email}
                  />

                  <RBForm.Group
                    controlId="ChangePassword"
                    className="row mb-10 mb-md-20 align-items-center"
                  >
                    <div
                      className={
                        "col-12 col-md-6 col-lg-4 text-md-end text-lg-start mb-10 mb-md-0"
                      }
                    >
                      <Label className={"mb-2 mb-md-0"}>Current password</Label>
                    </div>

                    <div className={"col-12 col-md-6 col-lg-5 col-xl-4"}>
                      <Input
                        type="password"
                        name="oldPassword"
                        value={values.oldPassword}
                        onChange={handleChange}
                        disabled={isSubmitting}
                        isInvalid={
                          !!touched.oldPassword && !!errors.oldPassword
                        }
                        isValid={touched.oldPassword && !errors.oldPassword}
                        autoComplete={"current-password"}
                      />

                      <Feedback type="invalid">
                        {touched.oldPassword && errors.oldPassword}
                      </Feedback>
                    </div>
                  </RBForm.Group>

                  <RBForm.Group
                    controlId="ChangePasswordNew"
                    className="row mb-10 mb-md-4 align-items-center"
                  >
                    <div
                      className={
                        "col-12 col-md-6 col-lg-4 text-md-end text-lg-start mb-10 mb-md-0"
                      }
                    >
                      <Label className={"mb-2 mb-md-0"}>New password</Label>
                    </div>

                    <div className={"col-12 col-md-6 col-lg-5 col-xl-4"}>
                      <Input
                        type="password"
                        name="newPassword"
                        disabled={isSubmitting}
                        value={values.newPassword}
                        onChange={handleChange}
                        isInvalid={
                          !!touched.newPassword && !!errors.newPassword
                        }
                        isValid={touched.newPassword && !errors.newPassword}
                        autoComplete="new-password"
                      />

                      <Feedback type="invalid">
                        {touched.newPassword && errors.newPassword}
                      </Feedback>
                    </div>
                  </RBForm.Group>

                  <RBForm.Group
                    controlId="ChangePasswordConfirm"
                    className="row align-items-center"
                  >
                    <div
                      className={
                        "col-12 col-md-6 col-lg-4 text-md-end text-lg-start mb-10 mb-md-0"
                      }
                    >
                      <Label className={"mb-2 mb-md-0"}>
                        Reenter new password
                      </Label>
                    </div>

                    <div className={"col-12 col-md-6 col-lg-5 col-xl-4"}>
                      <Input
                        type="password"
                        name="confirmPassword"
                        value={values.confirmPassword}
                        onChange={handleChange}
                        disabled={isSubmitting}
                        isInvalid={
                          !!touched.confirmPassword && !!errors.confirmPassword
                        }
                        isValid={
                          touched.confirmPassword && !errors.confirmPassword
                        }
                        autoComplete="new-password"
                      />

                      <Feedback type="invalid">
                        {touched.confirmPassword && errors.confirmPassword}
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

export default FormChangePassword;
