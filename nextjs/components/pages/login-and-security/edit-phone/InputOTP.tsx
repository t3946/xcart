import * as React from "react";
import { Form, Formik } from "formik";
import InnerPage from "@components/common/inner-page/InnerPage";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import { Form as RBForm } from "react-bootstrap";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import cn from "classnames";
import styles from "@components/pages/login-and-security/edit-phone/EditPhone.module.scss";
import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";
import * as yup from "yup";
import { editPhoneAction } from "@redux/actions/account-actions/LoginAndSecurityActions";
import { useDispatch } from "react-redux";

interface IProps {
  newPhone: string;
  secret: string;
  setStep: any;
}

const InputOTP: React.FC<IProps> = function (props: IProps) {
  const { newPhone, setStep, secret } = props;
  const dispatch = useDispatch();

  const validationSchema = yup.object().shape({
    code: yup.string().required("Required field"),
  });

  function submit(values: any, actions: any) {
    actions.setSubmitting(true);

    const data = {
      token: values.code,
      secret,
      step: "check-otp",
      phone: newPhone,
    };

    console.log({ data });
    dispatch(
      editPhoneAction({
        data,

        success(res: any) {
          actions.setSubmitting(false);

          if (res.data.error) {
            actions.setErrors({ code: res.data.error });
            return;
          }

          setStep("change-phone");
        },
      })
    );
  }

  const initialValues = {
    code: "",
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
              header={"Input one time password"}
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
                  cancelText={"back"}
                  onCancel={() => {
                    setStep("send-otp");
                  }}
                />
              }
            >
              <div className="px-10 px-md-0">
                <p className={cn("form-info", styles.currentPhoneText, "mb-0")}>
                  New phone number will:{" "}
                  <b
                    className={"cursor-pointer"}
                    onClick={() => setStep("send-otp")}
                  >
                    {newPhone}
                  </b>
                </p>

                <p className="form-info">
                  We have sent you otp. You must type it in field below.
                </p>

                <RBForm.Group
                  controlId={"EditUserPHone"}
                  className={cn("row", StylesLoginAndSecurity.formContainer)}
                >
                  <div
                    className={
                      "col-12 col-md-6 col-lg-6 mb-10 mb-md-0 d-flex align-items-center justify-content-md-end justify-content-lg-start"
                    }
                  >
                    <Label>OTP</Label>
                  </div>

                  <div className={"col-12 col-md-6 col-lg-6"}>
                    <Input
                      type="text"
                      name="code"
                      value={values.code}
                      onChange={handleChange}
                      disabled={isSubmitting}
                      isInvalid={!!touched.code && !!errors.code}
                      isValid={touched.code && !errors.code}
                    />
                    <Feedback className="position-absolute" type="invalid">
                      {touched.code && errors.code}
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

export default InputOTP;
