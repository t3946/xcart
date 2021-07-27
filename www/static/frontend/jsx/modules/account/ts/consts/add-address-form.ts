import * as Yup from "yup";

export const initialAddAddressFormValue = {
  country: { value: "", viewValue: "Select country" },
  full_name: "",
  phone_number: "",
  street: "",
  detailed: "",
  city: "",
  state: { value: "", viewValue: "Select state" },
  zip: "",
  is_default: false,
};

export const addAddressFormValidationSchema = Yup.object().shape({
  country: Yup.object().required("Required field"),
  full_name: Yup.string().required("Required field"),
  phone_number: Yup.string().required("Required field"),
  street: Yup.string().required("Required field"),
  city: Yup.string().required("Required field"),
  state: Yup.object().required("Required field"),
  zip: Yup.string().required("Required field"),
});
