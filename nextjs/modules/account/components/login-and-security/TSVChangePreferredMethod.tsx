import React from "react";
import InnerPage from "@modules/account/components/shared/InnerPage";
import { Form, Formik } from "formik";
import { getCountryByCode } from "@utils/Countries";
import { editPhoneAction } from "@redux/actions/account-actions/LoginAndSecurityActions";
import * as yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import FormInputPhone from "@modules/account/components/shared/FormInputPhone";

import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";

const TSVChangePreferredMethod: React.FC<any> = function () {
  const countries = useSelector((e: StoreInterface) => e.countries);
  const dispatch = useDispatch();

  const initialValues = {
    phoneCountryCode: "",
    phone: "",
    phoneExt: "",
  };

  const validationSchema = yup.object().shape({
    phone: yup.string().required("Phone is a required field"),
    phoneExt: yup.string(),
  });

  function submit(values, actions) {
    const phoneCode = getCountryByCode(
      values.phone_country_code,
      countries
    ).phone_code;
    const form = {
      phone_country_code: values.phone_country_code,
      phone: `+${phoneCode}${values.phone}`,
    };

    dispatch(
      editPhoneAction({
        form,

        success(res) {},

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
    <InnerPage
      header={"Change preferred method"}
      hat={
        <p className={"mb-0 px-10 px-md-0"}>
          If you would like to change your preferred method, you can do so by
          selecting a new or an existing device. This device should be available
          whenever you sign in to your S3 Stores account.
        </p>
      }
      bodyClasses={["p-0", StylesLoginAndSecurity.pageBody]}
      headerClasses={"mb-3"}
    >
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
              <div className="content-panel">
                <h3>Use your phone as a 2SV authenticator</h3>

                <p>
                  Tell us a phone number you'd like to use for 2SV
                  authentication challenges.
                </p>

                <p>Where should we send the One Time Password (OTP)?</p>

                <FormInputPhone
                  setFieldValue={setFieldValue}
                  handleChange={handleChange}
                  touched={touched}
                  errors={errors}
                  name={"phone"}
                  values={{
                    phoneCountryCode: "RU",
                    phone: values.phone,
                    phoneExt: values.phoneExt,
                  }}
                  mode={"mobile"}
                  label={"New Mobile number"}
                  classes={{ select: "col-1", container: "flex-nowrap" }}
                />

                <p className="form-info mt-3 mb-0">
                  By enrolling a mobile phone number, you consent to receive
                  automated text messages from or on behalf of S3 Stores related
                  to account management and security. Remove your number in{" "}
                  <b>Login & Security</b> to cancel. Message and data rates may
                  apply.
                </p>
              </div>

              <div className="text-center text-lg-start account-page-footer">
                <button
                  className={
                    "admin-form-control form-button form-button__wide w-md-auto d-inline-block"
                  }
                  disabled={isSubmitting}
                  type="submit"
                >
                  continue
                </button>
              </div>
            </Form>
          );
        }}
      </Formik>
    </InnerPage>
  );
};

export default TSVChangePreferredMethod;
