import React, { ChangeEvent, FocusEventHandler } from "react";
import { Grid } from "@material-ui/core";
import classnames from "classnames";
import { FormikErrors, FormikTouched } from "formik";
import InputMask from "react-input-mask";

interface FormInputPropsDto {
  label?: string;
  placeholder?: string;
  name: string;
  id?: string;
  type?: string;
  errorMessage?: string | FormikErrors<any> | string[] | FormikErrors<any>[];
  handleChange: (e: string | ChangeEvent<any>) => void;
  classes?: {
    group?: string | string[] | null;
    label?: string | string[] | null;
    input?: string | string[] | null;
  };
  value: any;
  touched?: boolean | FormikTouched<any> | FormikTouched<any>[];
  handleBlur?: FocusEventHandler<HTMLInputElement>;
  mask?: string;
}

export const FormInput: React.FC<FormInputPropsDto> = ({
  label,
  placeholder,
  classes,
  name,
  id,
  type,
  errorMessage,
  handleChange,
  value,
  touched,
  handleBlur,
  mask,
}) => {
  const error = errorMessage && touched;
  return (
    <div className={classnames("form-input-container", classes?.group)}>
      <Grid
        container
        justifyContent={label ? "space-between" : "flex-end"}
        alignItems="center"
      >
        {label && (
          <label
            htmlFor={id}
            className={classnames(
              "form-input-label",
              classes?.label,
              `${error && "form-input-label-error"}`
            )}
          >
            {label}
          </label>
        )}
        <div className={classnames(classes?.input)}>
          <InputMask
            mask={mask}
            value={value}
            onChange={handleChange}
            onBlur={handleBlur}
          >
            {(inputProps) => (
              <input
                onBlur={handleBlur}
                placeholder={placeholder}
                className={classnames(
                  "form-input",
                  `${error && "form-input-error"}`
                )}
                name={name}
                id={id}
                type={type ? type : "text"}
                onChange={handleChange}
                value={value}
              />
            )}
          </InputMask>
        </div>
      </Grid>
      <div className="error-message-input-container">
        <div className={classnames(classes?.input)}>
          {error && <div className="form-input-caption">{errorMessage}</div>}
        </div>
      </div>
    </div>
  );
};
