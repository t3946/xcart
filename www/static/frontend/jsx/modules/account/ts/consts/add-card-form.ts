import * as Yup from "yup";

export const initialAddCardFormValue = {
  cardNumber: "",
  name: "",
  expiration_month: { value: "01", viewValue: "01" },
  expiration_year: { value: 2021, viewValue: 2021 },
  is_default: false,
};

export const addCardFormValidationSchema = Yup.object().shape({
  cardNumber: Yup.object().required("Required field"),
  name: Yup.string().required("Required field"),
});
