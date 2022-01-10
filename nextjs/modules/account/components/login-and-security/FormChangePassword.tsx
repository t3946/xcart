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
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import InnerPage from "@modules/account/components/shared/InnerPage";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";

import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";

const FormChangePassword = (): any => {
  const router = useRouter();
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);
  const initialValues = {
    old_password: "",
    new_password: "",
    confirm_password: "",
  };

  const validationSchema = yup.object().shape({
    old_password: yup.string().required("Old Password required"),
    new_password: yup
      .string()
      .required("New password required")
      .min(8, "Password must be at least 8 characters")
      .max(32, "Password must be at most 32 characters"),
    confirm_password: yup
      .string()
      .required("Password confirm required")
      .oneOf([yup.ref("new_password"), null], "Passwords must match"),
  });

  function submit(values: any, actions: any) {
    dispatch(
      changePasswordAction({
        form: values,

        success(res: any) {
          dispatch(userSetAction(res.user));
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
                  <p className="form-info">
                    Use the form below to change the password for your S3 Stores
                    account
                  </p>

                  <Input type="hidden" name="login" value={user.email} />

                  <RBForm.Group
                    controlId="ChangePassword"
                    className="row mb-10"
                  >
                    <div
                      className={
                        "col-12 col-md-6 col-lg-6 text-md-end text-lg-start mb-10 mb-md-0"
                      }
                    >
                      <Label className={"mb-2 mb-md-0"}>Current password</Label>
                    </div>

                    <div className={"col-12 col-md-6 col-lg-6"}>
                      <Input
                        type="password"
                        name="old_password"
                        value={values.old_password}
                        onChange={handleChange}
                        isInvalid={
                          !!touched.old_password && !!errors.old_password
                        }
                        isValid={touched.old_password && !errors.old_password}
                        autoComplete={"current-password"}
                      />

                      <Feedback type="invalid">
                        {touched.old_password && errors.old_password}
                      </Feedback>
                    </div>
                  </RBForm.Group>

                  <RBForm.Group
                    controlId="ChangePasswordNew"
                    className="row mb-10"
                  >
                    <div
                      className={
                        "col-12 col-md-6 col-lg-6 text-md-end text-lg-start mb-10 mb-md-0"
                      }
                    >
                      <Label className={"mb-2 mb-md-0"}>New password</Label>
                    </div>

                    <div className={"col-12 col-md-6 col-lg-6"}>
                      <Input
                        type="password"
                        name="new_password"
                        value={values.new_password}
                        onChange={handleChange}
                        isInvalid={
                          !!touched.new_password && !!errors.new_password
                        }
                        isValid={touched.new_password && !errors.new_password}
                        autoComplete="new-password"
                      />

                      <Feedback type="invalid">
                        {touched.new_password && errors.new_password}
                      </Feedback>
                    </div>
                  </RBForm.Group>

                  <RBForm.Group
                    controlId="ChangePasswordConfirm"
                    className="row"
                  >
                    <div
                      className={
                        "col-12 col-md-6 col-lg-6 text-md-end text-lg-start mb-10 mb-md-0"
                      }
                    >
                      <Label className={"mb-2 mb-md-0"}>
                        Reenter new password
                      </Label>
                    </div>

                    <div className={"col-12 col-md-6 col-lg-6"}>
                      <Input
                        type="password"
                        name="confirm_password"
                        value={values.confirm_password}
                        onChange={handleChange}
                        isInvalid={
                          !!touched.confirm_password &&
                          !!errors.confirm_password
                        }
                        isValid={
                          touched.confirm_password && !errors.confirm_password
                        }
                        autoComplete="new-password"
                      />

                      <Feedback type="invalid">
                        {touched.confirm_password && errors.confirm_password}
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
