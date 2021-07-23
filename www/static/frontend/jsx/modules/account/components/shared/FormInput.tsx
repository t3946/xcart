import React, { ChangeEvent, FocusEventHandler } from "react";
import { Grid } from "@material-ui/core";
import classnames from "classnames";

interface FormInputPropsDto {
  label?: string;
  placeholder?: string;
  name: string;
  id?: string;
  type?: string;
  errorMessage?: string;
  handleChange: (e: string | ChangeEvent<any>) => void;
  classes?: {
    group?: string | string[] | null;
    label?: string | string[] | null;
    input?: string | string[] | null;
  };
  value: any;
  touched?: boolean;
  handleBlur: FocusEventHandler<HTMLInputElement>;
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
}) => {
  return (
    <Grid
      className={classnames("form-input-container", classes?.group)}
      container
      justify={label ? "space-between" : "flex-end"}
      alignItems="center"
    >
      {label && (
        <label
          htmlFor={id}
          className={classnames("form-input-label", classes?.label)}
        >
          {label}
        </label>
      )}
      <div className={classnames(classes?.input)}>
        <input
          onBlur={handleBlur}
          placeholder={placeholder}
          className={classnames("form-input")}
          name={name}
          id={id}
          type={type ? type : "text"}
          onChange={handleChange}
          value={value}
        />
        {errorMessage && touched && (
          <div className="form-input-caption">{errorMessage}</div>
        )}
      </div>
    </Grid>
  );
};
