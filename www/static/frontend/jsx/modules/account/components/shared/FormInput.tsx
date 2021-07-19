import React from "react";
import { Grid } from "@material-ui/core";

export const FormInput = ({ label }) => {
  return (
    <Grid
      className="form-input-container"
      container
      justify="space-between"
      alignItems="center"
    >
      <div className="form-input-label">{label}</div>
      <input placeholder="Hello world" className="form-input" />
    </Grid>
  );
};
