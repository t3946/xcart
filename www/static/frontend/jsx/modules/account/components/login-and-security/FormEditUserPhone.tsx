import { useHistory } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import React, { useContext } from "react";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { editPhoneAction } from "@client/jsx/redux/actions/account-actions/LoginAndSecurityActions";
import { userSetAction } from "@client/jsx/redux/actions/account-actions/UserActions";
import { FormSelect } from "@client/modules/account/components/shared/FormSelect";
import { getCountryByCode } from "@client/jsx/utils/Countries";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";

const FormEditUserPhone = (): any => {
  const history = useHistory();
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const countries = useSelector((e: StoreDto) => e.countries);
  const { showSnackbar } = useContext(SnackbarContext);

  let initialCountryCode;

  if (user.phone_country_code) {
    const country = getCountryByCode(user.phone_country_code, countries);

    initialCountryCode = {
      viewValue: country.name + " +" + country.phone_code,
      previewValue: country.code + " +" + country.phone_code,
      value: country.code,
    };
  } else {
    initialCountryCode = { viewValue: "Code" };
  }

  const [countryCode, setCountryCode] = React.useState(initialCountryCode);

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
    phone_country_code: initialCountryCode,
  };

  const validationSchema = yup.object().shape({
    phone: yup.string().required("Name is a required field"),
    phone_country_code: yup.string().required("Name is a required field"),
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

        success(res) {
          dispatch(userSetAction(res.user));
          history.push(route("account:login-and-security"));
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

  function getSelectItems() {
    const codes = [];

    for (const country of countries) {
      if (country.phone_code) {
        codes.push({
          viewValue: country.name + " +" + country.phone_code,
          previewValue: country.code + " +" + country.phone_code,
          value: country.code,
        });
      }
    }

    return codes;
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
                <RBForm.Group controlId="EditUserPhone" className={"row"}>
                  <div
                    className={
                      "col-12 col-md-3 col-lg-3 text-md-end text-lg-start"
                    }
                  >
                    <RBForm.Label className={"form-input-label mb-1 mb-md-0"}>
                      New Mobile number
                    </RBForm.Label>
                  </div>

                  <div className={"col-4 col-md-3 col-lg-3"}>
                    <FormSelect
                      items={getSelectItems()}
                      classes={{ selectList: "form-select-list__fit-content" }}
                      value={countryCode}
                      onClick={(item) => {
                        setFieldValue("phone_country_code", item.value);
                        setCountryCode(item);
                      }}
                      name={"phone_country_code"}
                      id={"add-address-state"}
                    />
                  </div>

                  <div className={"col-8 col-md-6 col-lg-6"}>
                    <RBForm.Control
                      type="text"
                      name="phone"
                      value={values.phone}
                      onChange={handleChange}
                      className={"form-input"}
                      isInvalid={!!touched.phone && !!errors.phone}
                      isValid={touched.phone && !errors.phone}
                    />

                    <RBForm.Control.Feedback type="invalid">
                      {errors.phone}
                    </RBForm.Control.Feedback>
                  </div>
                </RBForm.Group>

                <p className="form-info">
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
