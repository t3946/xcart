import * as React from "react";
import { Form, Formik } from "formik";
import InnerPage from "@components/common/inner-page/InnerPage";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import { Form as RBForm } from "react-bootstrap";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import cn from "classnames";
import styles from "@components/pages/login-and-security/edit-email/EditEmail.module.scss";
import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";
import * as yup from "yup";
import {
  setAlertAction,
  editEmailAction,
} from "@redux/actions/account-actions/LoginAndSecurityActions";
import { useRouter } from "next/router";
import { useDispatch } from "react-redux";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
interface IProps {
  newEmail: string;
}

const InputPassword: React.FC<IProps> = function (props: IProps) {
  const { newEmail } = props;
  const router = useRouter();
  const dispatch = useDispatch();

  const validationSchema = yup.object().shape({
    password: yup.string().required("Required field"),
  });

  function submit(values: any, actions: any) {
    actions.setSubmitting(true);

    const data = {
      password: values.password,
      email: newEmail,
      step: "change-email",
    };

    dispatch(
      editEmailAction({
        data,

        success(res: any) {
          actions.setSubmitting(false);

          if (res.data.error) {
            actions.setErrors({ password: res.data.error });
            return;
          }

          dispatch(userSetAction(res.data.user));
          router.push("/login-and-security");

          dispatch(
            setAlertAction({
              variant: "success",
              message: "Your account was successfully modified",
            })
          );
        },
      })
    );
  }

  const initialValues = {
    password: "",
  };

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
              header={"Change your email address"}
              headerClasses={"text-center text-lg-start"}
              bodyClasses={["content-panel", StylesLoginAndSecurity.pageBody]}
              footer={
                <SubmitCancelButtonsGroup
                  submitText={"continue"}
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
                  Input account password for change email.
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
                    <Label>Password</Label>
                  </div>

                  <div className={"col-12 col-md-6 col-lg-6"}>
                    <Input
                      type="password"
                      name="password"
                      value={values.password}
                      onChange={handleChange}
                      disabled={isSubmitting}
                      isInvalid={!!touched.password && !!errors.password}
                      isValid={touched.password && !errors.password}
                    />
                    <Feedback className="position-absolute" type="invalid">
                      {touched.password && errors.password}
                    </Feedback>
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

export default InputPassword;
