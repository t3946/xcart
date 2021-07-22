import React from "react";
import { Grid } from "@material-ui/core";
import classnames from "classnames";

interface FormInputPropsDto {
  label?: string;
  placeholder?: string;
  name: string;
  id?: string;
  type?: string;
  errorMessage?: string;
  handleChange: () => void;
  classes?: {
    group: string | string[] | null;
    label: string | string[] | null;
  };
  width?: string;
  value: any;
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
  width = "100%",
  value,
}) => {
  return (
    <Grid
      className={classnames("form-input-container", classes?.group)}
      container
      justify="space-between"
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

      <input
        placeholder={placeholder}
        className="form-input"
        name={name}
        id={id}
        type={type ? type : "text"}
        onChange={handleChange}
        style={{
          width,
        }}
        value={value}
      />

      {errorMessage && <div className="form-input-caption">{errorMessage}</div>}
    </Grid>
  );
};
