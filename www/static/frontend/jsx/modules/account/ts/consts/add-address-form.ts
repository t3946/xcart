import * as Yup from "yup";

export const initialAddAddressFormValue = {
  country: { value: "", viewValue: "Select country" },
  name: "",
  phone: "",
  address_street: "",
  address: "",
  city: "",
  state: { value: "", viewValue: "Select state" },
  zip: "",
  default: false,
};

export const addAddressFormValidationSchema = Yup.object().shape({
  country: Yup.string().required("Required field"),
  name: Yup.string().required("Required field"),
  phone: Yup.string().required("Required field"),
  address_street: Yup.string().required("Required field"),
  address: Yup.string().required("Required field"),
  city: Yup.string().required("Required field"),
  state: Yup.string().required("Required field"),
  zip: Yup.string().required("Required field"),
});
