import React from "react";
import { EmailDialogHeader } from "@s3stores-mail/components/simple";
import { Button, Dialog, Grid } from "@material-ui/core";

export const EmailDialogError: React.FC<any> = ({
  handleClose,
  open,
  errorText,
}) => {
  return (
    <Dialog
      maxWidth={"xs"}
      fullWidth={true}
      onClose={handleClose}
      className="email-send-dialog"
      aria-labelledby="simple-dialog-title"
      open={open}
      PaperProps={{
        style: {
          borderRadius: 0,
        },
      }}
    >
      <EmailDialogHeader handleClose={handleClose}>
        <div className="email-send-header-children">
          <p>Error</p>
        </div>
      </EmailDialogHeader>
      <div className="email-send-body-wrapper">
        <p>{errorText}</p>
        <Grid container justify="flex-end">
          <Button onClick={handleClose} className="schedule-send-buttons-send">
            OK
          </Button>
        </Grid>
      </div>
    </Dialog>
  );
};
