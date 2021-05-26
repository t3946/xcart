import React, { useState } from "react";
import { ScheduleDialogContext } from "@s3stores-mail/contexts";
import { EmailDialogScheduleSend } from "@s3stores-mail/containers/email-dialog-schedule-send/EmailDialogScheduleSend";

export const EmailScheduleSendDialogHOC: (
  component: React.ReactNode
) => React.FC = (component) => {
  return () => {
    const [dialogOpen, setDialogOpen] = useState(false);

    const handleClickOpen = () => {
      setDialogOpen(true);
    };

    const handleClose = () => {
      setDialogOpen(false);
    };

    const scheduleDialog = {
      open: dialogOpen,
      handleClickOpen,
      handleClose,
    };
    return (
      <ScheduleDialogContext.Provider value={scheduleDialog}>
        {component}
        <EmailDialogScheduleSend />
      </ScheduleDialogContext.Provider>
    );
  };
};
