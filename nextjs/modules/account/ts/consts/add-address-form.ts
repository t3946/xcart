import * as Yup from "yup";
import { getStates } from "@modules/account/utils/get-states";
import { phoneYupValidation } from "@modules/account/components/shared/FormInputPhone";

export const initialAddAddressFormValue = {
  country: { value: "", label: "Select country" },
  full_name: "",
  phone_numberCode: "",
  phone_number: "",
  phone_ext: "",
  street: "",
  detailed: "",
  city: "",
  state: { value: undefined, label: "Select state" },
  zip: "",
  is_default: false,
};

export const getAddAddressFormValidationSchema = (states) =>
  Yup.object().shape({
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
    phone_ext: Yup.string().max(4, "The maximum number of characters is 4"),
    street: Yup.string()
      .required("Required field")
      .max(50, "The maximum number of characters is 50"),
    detailed: Yup.string().max(50, "The maximum number of characters is 50"),
    city: Yup.string()
      .required("Required field")
      .max(50, "The maximum number of characters is 50"),
    state: Yup.object()
      .shape({ value: Yup.string() })
      .test("Required if there are states", "Required field", function (value) {
        return !(
          getStates(states, this.parent.country.value).length && !value.value
        );
      }),
    zip: Yup.string()
      .required("Required field")
      .max(50, "The maximum number of characters is 50"),
  });
