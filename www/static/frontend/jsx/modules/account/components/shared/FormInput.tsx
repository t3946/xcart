import React from "react";
import { Grid } from "@material-ui/core";
import classnames from "classnames";

export const FormInput = (props: any) => {
  const { label, placeholder, classes, name, id, type, caption, handleChange } =
    props;

  function labelTemplate() {
    if (label) {
      return (
        <label
          htmlFor={id}
          className={classnames("form-input-label", classes.label)}
        >
          {label}
        </label>
      );
    }
  }

  return (
    <Grid
      className={classnames("form-input-container", classes.group)}
      container
      justify="space-between"
      alignItems="center"
    >
      {labelTemplate()}

      <input
        placeholder={placeholder}
        className="form-input"
        name={name}
        id={id}
        type={type ? type : "text"}
        onChange={handleChange}
      />

      {caption && <div className="form-input-caption">{caption}</div>}
    </Grid>
  );
};
