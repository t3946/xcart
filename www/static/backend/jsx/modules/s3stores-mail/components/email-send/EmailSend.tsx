import React from "react";
import { Dialog } from "@material-ui/core";
import { EmailSendHeader } from "../email-send-header/EmailSendHeader";
import { EmailSendBody } from "../email-send-body/EmailSendBody";

export const EmailSend = ({ open, handleClose }) => {
  return (
    <Dialog
      maxWidth={"md"}
      className="email-send-dialog"
      fullWidth={true}
      onClose={handleClose}
      aria-labelledby="simple-dialog-title"
      open={open}
      PaperProps={{
        style: {
          borderRadius: 0,
        },
      }}
    >
      <div>
        <EmailSendHeader handleClose={handleClose} />
        <EmailSendBody />
      </div>
    </Dialog>
  );
};
