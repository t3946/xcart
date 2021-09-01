import * as Yup from "yup";

export const initialFormValue = {
  name: "",
  email: "",
  phone: "",
  phone_ext: "",
  question: "",
};

export const categoryFormValidationSchema = Yup.object().shape({
  name: Yup.string()
    .email("Введите email правильно")
    .required("Обязательное поле"),
  email: Yup.string().required("Обязательное поле"),
  phone: Yup.string().required("Обязательное поле"),
  phone_ext: Yup.string().required("Обязательное поле"),
  question: Yup.string().required("Обязательное поле"),
});
