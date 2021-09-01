import { useHistory } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import React, { useContext } from "react";
import { Formik, Form } from "formik";
import * as yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { editPhoneAction } from "@client/jsx/redux/actions/account-actions/LoginAndSecurityActions";
import { userSetAction } from "@client/jsx/redux/actions/account-actions/UserActions";
import { getCountryByCode } from "@client/jsx/utils/Countries";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import FormInputPhone from "@client/modules/account/components/shared/FormInputPhone";

const FormEditUserPhone = (props): any => {
  const history = useHistory();
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const countries = useSelector((e: StoreDto) => e.countries);
  const { showSnackbar } = useContext(SnackbarContext);

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
      phone: `+${phoneCode}${values.phone}`,
    };

    dispatch(
      editPhoneAction({
        form,

        success(res) {
          dispatch(userSetAction(res.user));
          const path =
            props.location.state?.from || route("account:login-and-security");
          history.push(path);
          showSnackbar({
            header: "Success",
            message: "You have successfully modified your account!",
            theme: "success",
          });
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
    <div>
      <div className="account-page_hat">
        <h1 className="text-center text-lg-start">
          Change Mobile Phone Number
        </h1>
      </div>

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
              </div>

              <div className="account-page_footer text-center text-lg-start">
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
    </div>
  );
};

export default FormEditUserPhone;
