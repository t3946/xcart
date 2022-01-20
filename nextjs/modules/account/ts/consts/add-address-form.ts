import * as Yup from "yup";

export const initialAddAddressFormValue = {
  country: { value: "", viewValue: "Select country" },
  full_name: "",
  phone_number: "",
  phone_numberExt: "",
  street: "",
  detailed: "",
  city: "",
  state: { value: undefined, viewValue: "Select state" },
  zip: "",
  is_default: false,
};

export const addAddressFormValidationSchema = Yup.object().shape({
  country: Yup.object().shape({
    value: Yup.string().required("Required field"),
  }),
  full_name: Yup.string()
    .required("Required field")
    .max(50, "The maximum number of characters is 50"),
  phone_number: Yup.string()
    .required("Required field")
    .max(50, "The maximum number of characters is 50")
    .matches(/[(]\d{3}[)] \d{3}-\d{4}/, "Is not in correct format"),
  phone_numberExt: Yup.string().max(5, "The maximum number of characters is 5"),
  street: Yup.string()
    .required("Required field")
    .max(50, "The maximum number of characters is 50"),
  detailed: Yup.string().max(50, "The maximum number of characters is 50"),
  city: Yup.string()
    .required("Required field")
    .max(50, "The maximum number of characters is 50"),
  state: Yup.object().shape({
    value: Yup.string().required("Required field"),
  }),
  zip: Yup.string()
    .required("Required field")
    .max(50, "The maximum number of characters is 50"),
});
