import * as Yup from "yup";

export const initialFormValue = {
  name: "",
  email: "",
  phone: "",
  phone_ext: "",
  question: "",
};

export const categoryFormValidationSchema = Yup.object().shape({
  name: Yup.string().required("Required field"),
  email: Yup.string().required("Required field").email("Invalid email"),
  phone: Yup.string().required("Required field").min(6, "Invalid phone"),
  phone_ext: Yup.string().required("Required field").min(3, "Invalid phone"),
  question: Yup.string().required("Required field"),
});
