import React, { useContext } from "react";
import { EmailDialogHeader } from "@s3stores-mail/components/simple/email-dialog-header/EmailDialogHeader";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";

export const EmailDialogScheduleSendHeader: React.FC = () => {
  const dialog = useContext(EmailDialogContext);
  return (
    <EmailDialogHeader handleClose={dialog.handleClose}>
      <span className="schedule-header-text">
        Schedule send (in Distributor Time)
      </span>
    </EmailDialogHeader>
  );
};
