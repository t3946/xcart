import React, { useContext } from "react";
import { Dialog } from "@material-ui/core";
import { EmailSendHeader } from "../email-send-header/EmailSendHeader";
import { EmailSendBody } from "../email-send-body/EmailSendBody";
import { EmailSendDialogContext } from "@s3stores-mail/contexts";

export const EmailSend: React.FC = () => {
  const dialog = useContext(EmailSendDialogContext);
  return (
    <Dialog
      maxWidth={"md"}
      className="email-send-dialog"
      fullWidth={true}
      onClose={dialog.handleClose}
      aria-labelledby="simple-dialog-title"
      open={dialog.open}
      PaperProps={{
        style: {
          borderRadius: 0,
          height: 615,
        },
      }}
    >
      <div>
        <EmailSendHeader />
        <EmailSendBody />
      </div>
    </Dialog>
  );
};
