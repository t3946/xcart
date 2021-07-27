import React from "react";
import { Grid } from "@material-ui/core";

export const BillingAddressListItem = ({ id, value, viewValue, name }) => {
  return (
    <Grid
      alignContent={"center"}
      container
      className="billing-address-item-container form_radio"
    >
      <input name={name} id={id} type="radio" />
      <label htmlFor={id}>{viewValue}</label>
    </Grid>
  );
};
