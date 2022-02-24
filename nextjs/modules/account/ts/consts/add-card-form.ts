import * as Yup from "yup";

export const initialAddCardFormValue = {
  cardNumber: "",
  cardHolderName: "",
  address: "",
  expiration_month: { value: "01", viewValue: "01" },
  expiration_year: { value: 2021, viewValue: 2021 },
  is_default: false,
};

export const addCardFormValidationSchema = Yup.object().shape({
  address: Yup.string().required("Address is required"),
  cardHolderName: Yup.string().required("Required field"),
});

export const editCardFormValidationSchema = Yup.object().shape({
  cardHolderName: Yup.string().required("Required field"),
});
