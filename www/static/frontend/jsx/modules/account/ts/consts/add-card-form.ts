import * as Yup from "yup";

export const initialAddCardFormValue = {
  cardNumber: "",
  name: "",
  expiration_month: { value: "01", viewValue: "01" },
  expiration_year: { value: 2021, viewValue: 2021 },
  is_default: false,
};

export const addCardFormValidationSchema = Yup.object().shape({
  cardNumber: Yup.string()
    .required("Required field")
    .matches(
      /^(?:4\d{3}|5[1-5]\d{2}|6011|3[47]\d{2})([-\s]?)\d{4}\1\d{4}\1\d{3,4}$/,
      "Enter a valid card"
    ),
  name: Yup.string().required("Required field"),
});

export const editCardFormValidationSchema = Yup.object().shape({
  name: Yup.string().required("Required field"),
});
