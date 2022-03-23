import React from "react";
import Select from "@modules/ui/forms/select/Select";
import Feedback from "@modules/ui/forms/Feedback";
import { useFormik } from "formik";
import FormInputPhone from "@modules/account/components/shared/FormInputPhone";
import {
  initialAddAddressFormValue,
  getAddAddressFormValidationSchema,
} from "@modules/account/ts/consts/add-address-form";
import { useDispatch } from "react-redux";
import {
  addAddress,
  editAddress,
} from "@redux/actions/account-actions/AddressActions";
import { getStates } from "@modules/account/utils/get-states";
import { getCountryByCode } from "@utils/Countries";
import { formatPhone, getPhoneCountryCode } from "@utils/phoneNumber";
import cn from "classnames";
import Styles from "@modules/account/components/addresses/AddAddressForm.module.scss";
import InputGroup from "./InputGroup";
import Checkbox from "@modules/ui/forms/Checkbox";
import { useSnackbar } from "@modules/account/hooks/useSnackbar";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import ErrorFocus from "@components/common/form-validation-focus/focusFormikHook";

function getCountryById(countries: any, countryId: number) {
  for (const country of countries) {
    if (country.country_id === countryId) {
      return country;
    }
  }

  return null;
}

function getStateById(states: any, stateId: number) {
  for (const state of states) {
    if (state.value === stateId) {
      return state;
    }
  }

  return null;
}

export const AddAddressForm: React.FC<any> = ({
  addressInfo = undefined,
  onCancelClick = undefined,
  children,
}) => {
  const dispatch = useDispatch();
  const countries = useSelectorAccount((e) => e.countries);
  const states = useSelectorAccount((e) => e.main.states);
  const snackbar = useSnackbar();
  const user = useSelectorAccount((e) => e.user);
  const addressFormLoading = useSelectorAccount(
    (e: any) => e.addresses.addressFormLoading
  );
  const onPended = () => {
    onCancelClick();
    snackbar.show(`${!addressInfo ? "Address added!" : "Address edit!"}`);
  };

  function submit() {
    const phoneCode = getCountryByCode(
      formik.values.phone_numberCode,
      countries
    ).phone_code;
    const data = {
      ...formik.values,
      phone_number: `+${phoneCode}${formik.values.phone_number.replace(
        /[+()\-\s]/gim,
        ""
      )}`,

      country_id: formik.values.country.value,
      state_id: formik.values.state.value,
      state: formik.values.state.value || null,
    };

    delete data.state;
    delete data.country;
    delete data.phone_numberCode;
    data.is_default = data.is_default ? 1 : 0;

    if (addressInfo) {
      dispatch(editAddress(data, onPended));
    } else {
      dispatch(addAddress(data, onPended, user.userId));
    }
  }

  if (addressInfo) {
    const country = getCountryById(countries, addressInfo.country_id);

    addressInfo.country = {
      value: country.country_id,
      label: country.name,
    };

    if (addressInfo.state_id) {
      const state = getStateById(states, addressInfo.state_id);

      console.log({state})

      addressInfo.state = {
        value: state.value,
        label: state.label,
      };
    }
  }

  const initialValues =
    (addressInfo && {
      ...addressInfo,
      detailed: addressInfo.detailed ?? "",
      phone_numberCode: getPhoneCountryCode(
        addressInfo.phone_number,
        countries
      ),
      phone_number: formatPhone(addressInfo.phone_number),
      // country: getCountryById(addressInfo.),
    }) ||
    initialAddAddressFormValue;
  const formik = useFormik({
    initialValues,
    validationSchema: getAddAddressFormValidationSchema(states),
    onSubmit: submit,
  });
  const selectOptionsCountry = [];

  for (const country of countries) {
    selectOptionsCountry.push({
      value: country.country_id,
      label: country.name,
    });
  }

  return (
    <div>
      <form
        onSubmit={formik.handleSubmit}
        className="your-order-form"
        encType="multipart/form-data"
      >
        <ErrorFocus formik={formik} />
        <InputGroup
          label="Country"
          error={formik.touched.country && formik.errors.country}
          component={
            <div>
              <Select
                name="country"
                clearable={false}
                classes={{
                  indicatorSeparator: "d-none",
                  valueContainer: "ps-0",
                }}
                options={selectOptionsCountry}
                value={formik.values.country}
                isValid={!!formik.touched.country && !formik.errors.country}
                isInvalid={!!formik.touched.country && !!formik.errors.country}
                onChange={(e) => {
                  formik.setFieldValue("country", e.target.value);
                  formik.setFieldValue(
                    "state",
                    initialAddAddressFormValue.state
                  );
                  formik.setFieldValue("country", e.target.value);
                }}
              />

              {!!formik.touched.country && !!formik.errors.country && (
                <Feedback className="position-absolute d-block" type="invalid">
                  {formik.errors.country}
                </Feedback>
              )}
            </div>
          }
        />
        <InputGroup
          label="Full Name (First and Last name)"
          placeholder={"Albert H. Einstein"}
          value={formik.values.full_name}
          name={"full_name"}
          error={formik.touched.full_name && formik.errors.full_name}
          isInvalid={!!(formik.touched.full_name && formik.errors.full_name)}
          isValid={!!(formik.touched.full_name && !formik.errors.full_name)}
          handleChange={formik.handleChange}
        />
        <InputGroup
          label="Phone Number"
          component={
            <FormInputPhone
              setFieldValue={formik.setFieldValue}
              handleChange={formik.handleChange}
              touched={formik.touched}
              errors={formik.errors}
              name={"phone_number"}
              values={formik.values}
              mode={"ext"}
            />
          }
        />

        <InputGroup
          placeholder="Street address or P.O. Box"
          label="Address"
          value={formik.values.street}
          name={"street"}
          error={formik.touched.street && formik.errors.street}
          isInvalid={!!(formik.touched.street && formik.errors.street)}
          isValid={!!(formik.touched.street && !formik.errors.street)}
          handleChange={formik.handleChange}
          touched={formik.touched.street}
          classes={{ group: "mb-1" }}
          handleBlur={formik.handleBlur}
        />

        <InputGroup
          placeholder="Apt, suite, unit, building, floor, etc."
          value={formik.values.detailed}
          name={"detailed"}
          error={formik.touched.detailed && formik.errors.detailed}
          isInvalid={!!(formik.touched.detailed && formik.errors.detailed)}
          isValid={!!(formik.touched.detailed && !formik.errors.detailed)}
          handleChange={formik.handleChange}
          touched={formik.touched.detailed}
          classes={{ grid: "justify-content-end" }}
          handleBlur={formik.handleBlur}
        />

        <InputGroup
          label={"City"}
          placeholder="Jackson"
          value={formik.values.city}
          name={"city"}
          error={formik.touched.city && formik.errors.city}
          isInvalid={!!(formik.touched.city && formik.errors.city)}
          isValid={!!(formik.touched.city && !formik.errors.city)}
          handleChange={formik.handleChange}
        />
        <InputGroup
          label={"State/Province"}
          error={formik.touched.state && formik.errors.state}
          component={
            <div>
              <Select
                name={"state"}
                clearable={false}
                classes={{
                  indicatorSeparator: "d-none",
                  valueContainer: "ps-0",
                }}
                options={getStates(states, formik.values.country.value)}
                value={formik.values.state}
                isValid={!!formik.touched.state && !formik.errors.state}
                isInvalid={!!formik.touched.state && !!formik.errors.state}
                onChange={(e) => {
                  formik.setFieldValue("state", e.target.value);
                  delete formik.errors.state;
                }}
              />
              {!!formik.touched.state && !!formik.errors.state && (
                <Feedback className="position-absolute d-block" type="invalid">
                  {formik.errors.state}
                </Feedback>
              )}
            </div>
          }
        />

        <InputGroup
          label={"Zip/Postal Code"}
          placeholder="39213"
          value={formik.values.zip}
          name={"zip"}
          error={formik.touched.zip && formik.errors.zip}
          isInvalid={!!(formik.touched.zip && formik.errors.zip)}
          isValid={!!(formik.touched.zip && !formik.errors.zip)}
          handleChange={formik.handleChange}
        />
        <InputGroup
          classNames={{ container: "m-0" }}
          component={
            <Checkbox
              label={
                <span className={Styles.checboxLabel}>
                  Make this my default address
                </span>
              }
              checked={formik.values.is_default}
              name={"is_default"}
              onChange={formik.handleChange}
              classes={{ container: "mt-20 mb-4" }}
            />
          }
        />
        <InputGroup
          classNames={{ container: "m-0" }}
          component={
            <button
              disabled={addressFormLoading}
              type={"submit"}
              className={cn(Styles.button, "form-button", "w-md-auto")}
            >
              {addressInfo ? "Save changes" : "Add Address"}
            </button>
          }
        />
        <div className={Styles.addAddressInputContainer}>
          <div>{children}</div>
        </div>
      </form>
    </div>
  );
};
