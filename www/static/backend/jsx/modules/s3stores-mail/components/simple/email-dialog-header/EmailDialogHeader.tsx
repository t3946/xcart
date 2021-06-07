import React from "react";
import { Grid, IconButton } from "@material-ui/core";
import ClearIcon from "@material-ui/icons/Clear";

interface DialogHeaderPropsDto {
  children: React.ReactNode;
  handleClose: () => void;
}

export const EmailDialogHeader: React.FC<DialogHeaderPropsDto> = ({
  children,
  handleClose,
}) => {
  return (
    <Grid
      className="email-send-header-wrapper"
      container
      justify="space-between"
      alignItems="center"
    >
      {children}

      <Grid>
        <IconButton onClick={handleClose}>
          <ClearIcon />
        </IconButton>
      </Grid>
    </Grid>
  );
};
