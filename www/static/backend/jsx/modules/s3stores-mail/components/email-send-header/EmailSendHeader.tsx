import React from "react";
import { FormControl, Grid, IconButton } from "@material-ui/core";
import ClearIcon from "@material-ui/icons/Clear";
import { EmailSelectSend } from "../email-select-send/EmailSelectSend";

export const EmailSendHeader = ({ handleClose }) => {
  return (
    <Grid
      className="email-send-header-wrapper"
      container
      justify="space-between"
      alignItems="center"
    >
      <Grid alignItems="center" container xs={5} justify="space-around">
        <Grid>Select template:</Grid>
        <Grid>
          <EmailSelectSend />
        </Grid>
      </Grid>

      <Grid>
        <IconButton onClick={handleClose}>
          <ClearIcon />
        </IconButton>
      </Grid>
    </Grid>
  );
};
