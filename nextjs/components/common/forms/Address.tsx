import React from "react";
import Select from "@modules/ui/forms/select/Select";
import FormInputPhone, {
  phoneExtYupValidation,
  phoneYupValidation,
} from "@modules/account/components/shared/FormInputPhone";
import {getCountryByCode} from "@utils/Countries";
import {Form, Formik} from "formik";
import {getStates} from "@modules/account/utils/get-states";
import {useDispatch} from "react-redux";
import {getTerritory} from "@redux/actions/account-actions/MainActions";
import FormGroup from "@modules/ui/forms/FormGroup";
import Input from "@modules/ui/forms/Input";
import {
  addAddress,
  editAddress,
} from "@redux/actions/account-actions/AddressActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import ErrorFocus from "@components/common/form-validation-focus/focusFormikComponent";
import * as Yup from "yup";
import InputGroup from "@modules/account/components/addresses/InputGroup";
import Checkbox from "@modules/ui/forms/Checkbox";
import cn from "classnames";
import {formatPhone} from "@utils/phoneNumber";

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

interface IProps {
  onSubmitted: () => void;
  footerTemplate: (isSubmitting: boolean) => any;
  addressType: string;
  canBeDefault?: boolean;
  editMode?: boolean;
  address?: any;
}

export const Address: React.FC<IProps> = (props) => {
  const {
    onSubmitted,
    footerTemplate,
    addressType,
    canBeDefault = false,
    editMode = false,
    address = null,
  } = props;
  const dispatch = useDispatch();
  const countries = useSelectorAccount((e) => e.countries);
  const states = useSelectorAccount((e) => e.main.states);
  const selectOptionsCountry: any = [];
  let initialValues = {
    country: {value: "", label: "Select country"},
    full_name: "",
    phone_numberCode: "",
    phone_number: "",
    phone_ext: "",
    street: "",
    detailed: "",
    city: "",
    state: {value: "", label: "Select state"},
    zip: "",
    is_default: false,
    address_type: addressType,
  };

  for (const country of countries) {
    selectOptionsCountry.push({
      value: country.country_id,
      label: country.name,
    });
  }

  const validationSchema = Yup.object().shape({
    country: Yup.object()
      .shape({
        value: Yup.string(),
      })
      .test("Value required", "Required field", (selectedCountry) => {
        return Boolean(selectedCountry.value);
      }),
    full_name: Yup.string()
      .required("Required field")
      .max(50, "The maximum number of characters is 50"),
    phone_numberCode: Yup.string().required("Required field"),
    phone_number: phoneYupValidation,
    phone_ext: phoneExtYupValidation,
    street: Yup.string()
      .required("Required field")
      .max(50, "The maximum number of characters is 50"),
    detailed: Yup.string().max(50, "The maximum number of characters is 50"),
    city: Yup.string()
      .required("Required field")
      .max(50, "The maximum number of characters is 50"),
    state: Yup.object()
      .test("Value required", "Required field", (selectedCountry) => {
        return Boolean(selectedCountry.value);
      })
      .shape({value: Yup.string()}),
    zip: Yup.string()
      .required("Required field")
      .max(50, "The maximum number of characters is 50"),
  });

  function submit(values: Record<any, any>) {
    const phoneCountry = getCountryByCode(values.phone_numberCode, countries);
    const data = {
      ...values,
      phone_number: `+${phoneCountry.phone_code}${values.phone_number.replace(
        /[+()\-\s]/gim,
        ""
      )}`,
      country_id: values.country.value,
      state_id: values.state.value,
      phone_number_country_id: phoneCountry.country_id,
    };

    delete data.country;
    delete data.phone_numberCode;
    delete data.state;

    if (editMode) {
      dispatch(editAddress(data, onSubmitted));
    } else {
      dispatch(addAddress(data, onSubmitted));
    }
  }

  if (editMode) {
    initialValues = {...address};

    const country = getCountryById(countries, address.country_id);

    initialValues.country = {
      value: country.country_id,
      label: country.name,
    };

    if (address.state_id) {
      const state = getStateById(states, address.state_id);

      initialValues.state = {
        value: state.value,
        label: state.label,
      };
    }

    const phoneCountry = getCountryById(
      countries,
      address.phone_number_country_id
    );

    initialValues.phone_numberCode = phoneCountry.code;

    initialValues.phone_number = formatPhone(address.phone_number);
  }

  React.useEffect(() => {
    dispatch(getTerritory());
  }, []);

  return (
    <Formik
      initialValues={initialValues}
      validationSchema={validationSchema}
      onSubmit={submit}
    >
      {({
          errors,
          setFieldValue,
          values,
          touched,
          handleChange,
          isSubmitting,
          setValues,
        }) => {
        const {country, state} = values;
        const statesForSelectedCountry = getStates(
          states,
          values.country.value
        );

        if (
          country.value &&
          !state.value &&
          statesForSelectedCountry.length === 1
        ) {
          values.state = statesForSelectedCountry[0];
          setValues(values);
        }

        return (
          <Form className="your-order-form" encType="multipart/form-data">
            <ErrorFocus />
            <FormGroup
              input={
                <Select
                  name="country"
                  clearable={false}
                  options={selectOptionsCountry}
                  value={values.country}
                  onChange={(e) => {
                    setFieldValue("country", e.target.value);
                    setFieldValue("state", initialValues.state);
                  }}
                  isValid={!!touched.country && !errors.country}
                  isInvalid={!!touched.country && !!errors.country}
                  classes={{
                    valueContainer: "ps-0",
                  }}
                />
              }
              label="Country"
              error={!!touched.country && errors.country}
            />

            <FormGroup
              input={
                <Input
                  value={values.full_name}
                  name={"full_name"}
                  onChange={handleChange}
                  placeholder={"Albert H. Einstein"}
                  isValid={!!touched.full_name && !errors.full_name}
                  isInvalid={!!touched.full_name && !!errors.full_name}
                />
              }
              label="Full Name (First and Last name)"
              error={!!touched.full_name && errors.full_name}
            />

            <FormGroup
              input={
                <FormInputPhone
                  setFieldValue={setFieldValue}
                  handleChange={handleChange}
                  touched={touched}
                  errors={errors}
                  name={"phone_number"}
                  values={values}
                  mode={"ext"}
                />
              }
              label="Phone Number"
            />

            <FormGroup
              input={
                <Input
                  value={values.street}
                  name={"street"}
                  onChange={handleChange}
                  placeholder={"Street address or P.O. Box"}
                  isValid={!!touched.street && !errors.street}
                  isInvalid={!!touched.street && !!errors.street}
                />
              }
              label="Address"
              error={!!touched.street && errors.street}
            />

            <FormGroup
              input={
                <Input
                  value={values.detailed}
                  name={"detailed"}
                  onChange={handleChange}
                  placeholder={"Apt, suite, unit, building, floor, etc."}
                  isValid={!!touched.detailed && !errors.detailed}
                  isInvalid={!!touched.detailed && !!errors.detailed}
                />
              }
              label=""
              error={!!touched.detailed && errors.detailed}
            />

            <FormGroup
              input={
                <Input
                  value={values.city}
                  name={"city"}
                  onChange={handleChange}
                  placeholder={"Jackson"}
                  isValid={!!touched.city && !errors.city}
                  isInvalid={!!touched.city && !!errors.city}
                />
              }
              label="City"
              error={!!touched.city && errors.city}
            />

            <FormGroup
              input={
                <Select
                  clearable={false}
                  options={getStates(states, values.country.value)}
                  value={values.state}
                  onChange={handleChange}
                  name={"state"}
                  isValid={!!touched.state && !errors.state}
                  isInvalid={!!touched.state && !!errors.state}
                  classes={{
                    valueContainer: "ps-0",
                  }}
                />
              }
              label="State/Province"
              error={!!touched.state && errors.state}
            />

            <FormGroup
              input={
                <Input
                  value={values.zip}
                  name={"zip"}
                  onChange={handleChange}
                  placeholder={"39213"}
                  isValid={!!touched.zip && !errors.zip}
                  isInvalid={!!touched.zip && !!errors.zip}
                />
              }
              label="Zip/Postal Code"
              error={!!touched.zip && errors.zip}
            />

            {canBeDefault && (
              <InputGroup
                classNames={{container: "m-0"}}
                component={
                  <Checkbox
                    label={
                      <span className={cn("fs-14", "fw-bold")}>
                        Make this my default address
                      </span>
                    }
                    checked={values.is_default}
                    name={"is_default"}
                    onChange={handleChange}
                    classes={{container: "mt-20 mb-4"}}
                  />
                }
              />
            )}

            {footerTemplate(isSubmitting)}
          </Form>
        );
      }}
    </Formik>
  );
};

export default Address;
