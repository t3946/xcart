import * as React from "react";
import { FormInputPropsDto } from "@/modules/shared/ts/types";
import { Field } from "formik";

const FormInput: React.FC<FormInputPropsDto> = ({
  name,
  label,
  type = "text",
  error = false,
  as,
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
        as={as ? "textarea" : null}
        className={`formik-input${error ? "-error" : ""}${
          valid ? "-valid" : ""
        } ${as ? "textarea" : ""}`}
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
