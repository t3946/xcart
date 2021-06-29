import React, { useContext } from "react";
import { Dialog } from "@material-ui/core";
import { EmailSendBodyContainer } from "@s3stores-mail/containers/email-send-body/EmailSendBody.container";
import { EmailSendHeaderContainer } from "@s3stores-mail/containers/email-send-header/EmailSendHeader.container";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";
export const EmailSend: React.FC = () => {
  const dialog = useContext(EmailDialogContext);
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
          minHeight: 650,
        },
      }}
    >
      <div>
        <EmailSendHeaderContainer />
        <EmailSendBodyContainer />
      </div>
    </Dialog>
  );
};
