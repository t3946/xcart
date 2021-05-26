import React, { useContext } from "react";
import { Dialog } from "@material-ui/core";
import { EmailSendDialogContext } from "@s3stores-mail/contexts";
import { EmailSendBodyContainer } from "@s3stores-mail/containers/email-send-body/EmailSendBody.container";
import { EmailSendHeaderContainer } from "@s3stores-mail/containers/email-send-header/EmailSendHeader.container";
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
        <EmailSendHeaderContainer />
        <EmailSendBodyContainer />
      </div>
    </Dialog>
  );
};
