import React, { Dispatch, ReactNode } from "react";
import { Grid } from "@material-ui/core";
import classnames from "classnames";

interface RadioBtnProps {
  id: number;
  groupValue: number;
  radioValue: number;
  viewValue: string | ReactNode;
  onChange: Dispatch<number>;
  name: string;
  groupClasses: {
    group: string | string[];
    checked: string | string[];
  };
}

export const RadioBtn: React.FC<RadioBtnProps> = ({
  id,
  groupValue,
  radioValue,
  viewValue,
  onChange,
  name,
  groupClasses,
}) => {
  return (
    <Grid
      alignContent={"center"}
      container
      onClick={() => onChange(radioValue)}
      className={
        classnames(groupClasses.group) +
        ` form-radio ${
          groupValue === radioValue && classnames(groupClasses.checked)
        }`
      }
    >
      <input
        value={radioValue}
        name={name}
        id={String(id)}
        type="radio"
        checked={groupValue === radioValue}
      />
      <label htmlFor={String(id)}>{viewValue}</label>
    </Grid>
  );
};
