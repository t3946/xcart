import React, { useContext } from "react";
import { EmailDialogHeader } from "../email-dialog-header/EmailDialogHeader";
import { ScheduleDialogContext } from "@s3stores-mail/contexts";

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
