import { useHistory } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import React from "react";
import { Formik, Form } from "formik";
import * as yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import {
  editPhoneAction,
  setAlertAction,
} from "@client/jsx/redux/actions/account-actions/LoginAndSecurityActions";
import { userSetAction } from "@client/jsx/redux/actions/account-actions/UserActions";
import { getCountryByCode } from "@client/jsx/utils/Countries";
import FormInputPhone from "@client/modules/account/components/shared/FormInputPhone";
import InnerPage from "@client/modules/account/components/shared/InnerPage";
import SubmitCancelButtonsGroup from "@client/modules/account/components/shared/SubmitCancelButtonsGroup";

const FormEditUserPhone = (props): any => {
  const history = useHistory();
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const countries = useSelector((e: StoreDto) => e.countries);

  /**
   * Get phone number without country code prefix
   */
  function getPhoneNumberInnerPart(countryCode, phoneNumber) {
    const phoneCountryCodePrefix =
      "+" + getCountryByCode(countryCode, countries).phone_code;
    return phoneNumber.replace(phoneCountryCodePrefix, "");
  }

  const initialValues = {
    phone: getPhoneNumberInnerPart(user.phone_country_code, user.phone),
    phoneCountryCode: user.phone_country_code,
  };

  const validationSchema = yup.object().shape({
    phone: yup.string().required("Name is a required field"),
    phoneCountryCode: yup.string().required("Name is a required field"),
  });

  function submit(values, actions) {
    const phoneCode = getCountryByCode(
      values.phoneCountryCode,
      countries
    ).phone_code;
    const form = {
      phone_country_code: values.phoneCountryCode,
      phone: `+${phoneCode}${values.phone}`.replace(/[()\-\s]/gim, ""),
    };

    dispatch(
      editPhoneAction({
        form,

        success(res) {
          dispatch(userSetAction(res.user));
          const path =
            props.location.state?.from || route("account:login-and-security");
          history.push(path);

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
              bodyClasses={"content-panel"}
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
                    history.push(route("account:login-and-security"));
                  }}
                />
              }
            >
              <FormInputPhone
                setFieldValue={setFieldValue}
                handleChange={handleChange}
                touched={touched}
                errors={errors}
                name={"phone"}
                values={{
                  phoneCountryCode: values.phoneCountryCode,
                  phone: values.phone,
                }}
                mode={"mobile"}
                label={"New Mobile number"}
              />

              <p className="form-info mb-0">
                By enrolling a mobile phone number, you consent to receive
                automated text messages from or on behalf of S3 Stores related
                to account management and security. Remove your number in{" "}
                <b>Login & Security</b> to cancel. Message and data rates may
                apply.
              </p>
            </InnerPage>
          </Form>
        );
      }}
    </Formik>
  );
};

export default FormEditUserPhone;
