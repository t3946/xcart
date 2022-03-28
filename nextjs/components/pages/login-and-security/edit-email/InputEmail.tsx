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
import { editEmailAction } from "@redux/actions/account-actions/LoginAndSecurityActions";
import { useRouter } from "next/router";
import { useDispatch } from "react-redux";

interface IProps {
  currentEmail: string;
  setStep: any;
  setSecret: any;
  setNewEmail: any;
}

const InputEmail: React.FC<IProps> = function (props: IProps) {
  const { currentEmail, setStep, setSecret, setNewEmail } = props;
  const router = useRouter();
  const dispatch = useDispatch();

  const validationSchema = yup.object().shape({
    email: yup
      .string()
      .required("Email is a required field")
      .email("Email must be a valid email"),
  });

  function submit(values: any, actions: any) {
    actions.setSubmitting(true);

    const data = {
      email: values.email,
      step: "send-otp",
    };

    dispatch(
      editEmailAction({
        data,

        success(res: any) {
          actions.setSubmitting(false);
          if (res.data.error) {
            actions.setErrors({ email: res.data.error });
            return;
          }

          setSecret(res.data.secret);
          setStep("check-otp");
          setNewEmail(data.email);
        },
      })
    );
  }

  const initialValues = {
    email: currentEmail,
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
                  Please enter a new email address you want to use with your
                  account, to which an OTP, or One Time Password, will be sent.
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
                    <Label>Email Address</Label>
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
  );
};

export default InputEmail;
