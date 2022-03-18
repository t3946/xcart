import * as React from "react";
import { Form, Formik } from "formik";
import InnerPage from "@components/common/inner-page/InnerPage";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import { Form as RBForm } from "react-bootstrap";
import Label from "@modules/ui/forms/Label";
import Feedback from "@modules/ui/forms/Feedback";
import cn from "classnames";
import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";
import * as yup from "yup";
import { editPhoneAction } from "@redux/actions/account-actions/LoginAndSecurityActions";
import { useRouter } from "next/router";
import { useDispatch } from "react-redux";
import FormInputPhone, {
  phoneYupValidation,
} from "@modules/account/components/shared/FormInputPhone";
import { formatPhone, getPhoneCountryCode } from "@utils/phoneNumber";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { getCountryByCode } from "@utils/Countries";

interface IProps {
  currentPhone: string;
  setStep: any;
  setSecret: any;
  setNewPhone: any;
}

const InputPhone: React.FC<IProps> = function (props: IProps) {
  const { currentPhone, setStep, setSecret, setNewPhone } = props;
  const router = useRouter();
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);
  const countries = useSelectorAccount((e) => e.countries);
  const validationSchema = yup.object().shape({
    phoneCode: yup.string().required("Country code is a required field"),
    phone: phoneYupValidation,
  });

  const initialValues = {
    phone: "",
    phoneCode: "",
  };

  if (user.phone) {
    initialValues.phone = formatPhone(user.phone) || "";
    initialValues.phoneCode = getPhoneCountryCode(user.phone, countries);
  }

  function submit(values: any, actions: any) {
    const country = getCountryByCode(values.phoneCode, countries);

    if (!country) {
      return;
    }

    actions.setSubmitting(true);

    const phoneCode = `+${country.phone_code}`;
    const phone = `${phoneCode}${values.phone}`.replace(/[()\-\s]/gim, "");

    const data = {
      phone,
      step: "send-otp",
    };

    dispatch(
      editPhoneAction({
        data,

        success(res: any) {
          actions.setSubmitting(false);
          if (res.data.error) {
            actions.setErrors({ phone: res.data.error });
            return;
          }

          setSecret(res.data.secret);
          setStep("check-otp");
          setNewPhone(data.phone);
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
      {function ({
        isSubmitting,
        setFieldValue,
        values,
        errors,
        touched,
        handleChange,
      }) {
        return (
          <Form>
            <InnerPage
              header={"Change Mobile Phone Number"}
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
                <RBForm.Group
                  controlId={"EditUserPhone"}
                  className={cn("row", StylesLoginAndSecurity.formContainer)}
                >
                  <div
                    className={
                      "col-12 col-md-6 col-lg-6 mb-10 mb-md-0 d-flex align-items-center justify-content-md-end justify-content-lg-start"
                    }
                  >
                    <Label>New phone number</Label>
                  </div>

                  <div className={"col-12 col-md-6 col-lg-6"}>
                    <FormInputPhone
                      setFieldValue={setFieldValue}
                      handleChange={handleChange}
                      disabled={isSubmitting}
                      touched={touched}
                      errors={errors}
                      name={"phone"}
                      values={values}
                      classes={{ select: "col-1", container: "flex-nowrap" }}
                      mode={"mobile"}
                    />
                  </div>
                </RBForm.Group>
                <p className="form-info mt-4 mb-0">
                  By enrolling a mobile phone number, you consent to receive
                  automated text messages from or on behalf of S3 Stores related
                  to account management and security. Remove your number in{" "}
                  <b>Login & Security</b> to cancel. Message and data rates may
                  apply.
                </p>
              </div>
            </InnerPage>
          </Form>
        );
      }}
    </Formik>
  );
};

export default InputPhone;
