import { useRouter } from "next/router";
import React from "react";
import { Formik, Form, FormikHelpers } from "formik";
import * as yup from "yup";
import { useDispatch } from "react-redux";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import {
  editPhoneAction,
  setAlertAction,
} from "@redux/actions/account-actions/LoginAndSecurityActions";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import { getCountryByCode } from "@utils/Countries";
import FormInputPhone from "@modules/account/components/shared/FormInputPhone";
import InnerPage from "@modules/account/components/shared/InnerPage";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";
import { AxiosResponse } from "axios";
import getPhoneNumberInnerPart from "@utils/getPhoneNumberInnerPart";
import Label from "@modules/ui/forms/Label";

interface IProps {
  location: any;
}

const FormEditUserPhone = (props: IProps): any => {
  const { location } = props;
  const router = useRouter();
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);
  const countries = useSelectorAccount((e) => e.countries);
  const validationSchema = yup.object().shape({
    phone: yup
      .string()
      .required("Phone is a required field")
      .matches(/[(]\d{3}[)] \d{3}[-]\d{4}/, "Is not in correct format"),
    phoneCountryCode: yup.string().required("Country code is a required field"),
  });

  function submit(values: Record<any, any>, actions: FormikHelpers<any>): void {
    const country = getCountryByCode(values.phoneCountryCode, countries);

    if (!country) {
      return;
    }

    const phoneCode = country.phone_code;

    dispatch(
      editPhoneAction({
        data: {
          phone: `+${phoneCode}${values.phone}`.replace(/[()\-\s]/gim, ""),
        },

        success(res: AxiosResponse) {
          dispatch(userSetAction(res.data.user));
          router.push(location?.state?.from || "/login-and-security");

          dispatch(
            setAlertAction({
              variant: "success",
              message: "You have successfully modified your account!",
            })
          );
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
    phone: "",
    phoneCountryCode: user.phone_country_code,
  };

  if (user.phone) {
    initialValues.phone = getPhoneNumberInnerPart(
      user.phone_country_code,
      user.phone,
      countries
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
              <div className="row px-10 px-md-0 align-items-center">
                <Label
                  className={
                    "text-md-end text-lg-start col-md-6 col-lg-4 mb-10 mb-md-0"
                  }
                >
                  New Mobile number
                </Label>
                <div className={"col-md-6 col-lg-7"}>
                  <FormInputPhone
                    setFieldValue={setFieldValue}
                    handleChange={handleChange}
                    disabled={isSubmitting}
                    touched={touched}
                    errors={errors}
                    name={"phone"}
                    values={{
                      phoneCountryCode: values.phoneCountryCode,
                      phone: values.phone,
                    }}
                    classes={{ select: "col-1", container: "flex-nowrap" }}
                    mode={"mobile"}
                  />
                </div>

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

export default FormEditUserPhone;
