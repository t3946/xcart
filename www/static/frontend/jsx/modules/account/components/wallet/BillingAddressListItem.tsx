import React, { Dispatch } from "react";
import { Grid } from "@material-ui/core";

interface BillingAddressListItemProps {
  id: number;
  groupValue: number;
  radioValue: number;
  viewValue: string;
  onChange: Dispatch<number>;
  name: string;
}

export const BillingAddressListItem: React.FC<BillingAddressListItemProps> = ({
  id,
  groupValue,
  radioValue,
  viewValue,
  onChange,
  name,
}) => {
  return (
    <Grid
      alignContent={"center"}
      container
      className={`billing-address-item-container form-radio ${
        groupValue === radioValue && "form-radio-checked"
      }`}
    >
      <input
        value={radioValue}
        onChange={() => onChange(radioValue)}
        name={name}
        id={id}
        type="radio"
        checked={groupValue === radioValue}
      />
      <label htmlFor={id}>{viewValue}</label>
    </Grid>
  );
};
