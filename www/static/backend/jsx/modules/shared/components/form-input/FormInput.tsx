import { Field } from "formik";
import React from "react";

const FormInput: React.FC<any> = ({
  name,
  label,
  type = "text",
  error = false,
  required = false,
  valid,
}) => {
  return (
    <div>
      <div>
        <span
          className={`formik-input${error ? "-error" : ""}${
            valid ? "-valid" : ""
          }-text`}
        >
          {label}
        </span>
        {required ? (
          <span className={"formik-input-text_required"}>*</span>
        ) : null}
      </div>
      <Field
        className={`formik-input${error ? "-error" : ""}${
          valid ? "-valid" : ""
        }`}
        autoComplete="off"
        error={error}
        label={label}
        name={name}
        fullWidth
        type={type}
      />
    </div>
  );
};

export default FormInput;
