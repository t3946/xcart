import React, { useContext } from "react";
import { ScheduleDialogContext } from "@s3stores-mail/contexts";
import { EmailDialogHeader } from "@s3stores-mail/components/simple/email-dialog-header/EmailDialogHeader";

export const EmailDialogScheduleSendHeader: React.FC = () => {
  const dialog = useContext(ScheduleDialogContext);
  return (
    <EmailDialogHeader handleClose={dialog.handleClose}>
      <span className="schedule-header-text">
        Schedule send (in Distributor Time)
      </span>
    </EmailDialogHeader>
  );
};
