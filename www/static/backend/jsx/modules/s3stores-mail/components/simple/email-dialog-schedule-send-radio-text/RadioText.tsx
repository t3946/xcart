import React from "react";
import { Grid } from "@material-ui/core";

interface RadioTextPropsDto {
  label: string;
  time: string;
}

export const RadioText: React.FC<RadioTextPropsDto> = ({ label, time }) => {
  return (
    <Grid className="radio-text-wrap" container justify={"space-between"}>
      <span className="radio-text-label">{label}</span>
      <span>{time}</span>
    </Grid>
  );
};
