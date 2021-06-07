import React, { useContext } from "react";
import { Dialog } from "@material-ui/core";
import { EmailDialogScheduleSendBodyContainer } from "@s3stores-mail/containers/email-dialog-schedule-send-body/EmailDialogScheduleSendBody.container";
import { EmailDialogScheduleSendHeader } from "@s3stores-mail/components/ordinary/email-dialog-schedule-send-header/EmailDialogScheduleSendHeader";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";

export const EmailDialogScheduleSend: React.FC = () => {
  const dialog = useContext(EmailDialogContext);
  return (
    <Dialog
      maxWidth={"sm"}
      className="email-send-dialog"
      onClose={dialog.handleClose}
      aria-labelledby="simple-dialog-title"
      open={dialog.open}
      PaperProps={{
        style: {
          borderRadius: 0,
        },
      }}
    >
      <EmailDialogScheduleSendHeader />
      <EmailDialogScheduleSendBodyContainer />
    </Dialog>
  );
};
