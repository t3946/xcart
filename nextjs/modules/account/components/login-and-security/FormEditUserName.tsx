import { useRouter } from "next/router";
import React from "react";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import { useDispatch } from "react-redux";
import {
  editNameAction,
  setAlertAction,
} from "@redux/actions/account-actions/LoginAndSecurityActions";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import InnerPage from "@modules/account/components/shared/InnerPage";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import cn from "classnames";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";

const FormEditUserName = (): any => {
  const router = useRouter();
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);

  function submit(values: any, actions: any) {
    dispatch(
      editNameAction({
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
    name: user.name,
  };
  const validationSchema = yup.object().shape({
    name: yup.string().required("Name is a required field"),
  });

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
              bodyClasses={["content-panel", StylesLoginAndSecurity.pageBody]}
              footerClasses={"text-center text-lg-start"}
              footer={
                <SubmitCancelButtonsGroup
                  submitText={"save changes"}
                  disabled={isSubmitting}
                  buttonAdvancedClasses={[
                    StylesLoginAndSecurity.button_editPage,
                    "form-button__submit-and-cancel p-0",
                  ]}
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
                  If you want to change the name associated with your S3 Stores
                  customer account, you may do so below. Be sure to click the{" "}
                  <b>Save Changes</b> button when you are done.
                </p>

                <RBForm.Group
                  controlId="EditUserName"
                  className={cn(["row", StylesLoginAndSecurity.formContainer])}
                >
                  <div
                    className={
                      "col-12 col-md-6 col-lg-6 text-md-end text-lg-start d-flex align-items-center justify-content-md-end justify-content-lg-start"
                    }
                  >
                    <Label
                      className={
                        "mb-10 mb-md-0 d-md-flex align-items-center justify-content-end justify-content-lg-start"
                      }
                    >
                      New Full Name
                    </Label>
                  </div>

                  <div className={"col-12 col-md-6 col-lg-6"}>
                    <Input
                      type="text"
                      name="name"
                      value={values.name}
                      onChange={handleChange}
                      className={"form-input"}
                      disabled={isSubmitting}
                      isInvalid={!!touched.name && !!errors.name}
                      isValid={touched.name && !errors.name}
                    />

                    {!!touched.name && !!errors.name && (
                      <Feedback className="position-absolute" type="invalid">
                        {errors.name}
                      </Feedback>
                    )}
                  </div>
                </RBForm.Group>
              </div>
            </InnerPage>
          </Form>
        );
      }}
    </Formik>
  );
};

export default FormEditUserName;
