import React from "react";
import { Grid } from "@material-ui/core";

export const DialogHeader = ({ label, onClose }) => {
  return (
    <Grid
      alignItems="center"
      container
      justifyContent="space-between"
      className="dialog-header"
    >
      <div className="dialog-header-label">{label}</div>
      <div className="dialog-header-cross">
        <img
          onClick={onClose}
          src={"/static/frontend/images/icons/account/cross.svg"}
        />
      </div>
    </Grid>
  );
};
