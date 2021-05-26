import React, { useContext } from "react";
import { Dialog } from "@material-ui/core";
import { ScheduleDialogContext } from "@s3stores-mail/contexts";
import { EmailDialogScheduleSendBodyContainer } from "@s3stores-mail/containers/email-dialog-schedule-send-body/EmailDialogScheduleSendBody.container";
import { EmailDialogScheduleSendHeader } from "@s3stores-mail/components/ordinary/email-dialog-schedule-send-header/EmailDialogScheduleSendHeader";

export const EmailDialogScheduleSend: React.FC = () => {
  const dialog = useContext(ScheduleDialogContext);
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
      <div>
        <EmailDialogScheduleSendHeader />
        <EmailDialogScheduleSendBodyContainer />
      </div>
    </Dialog>
  );
};
