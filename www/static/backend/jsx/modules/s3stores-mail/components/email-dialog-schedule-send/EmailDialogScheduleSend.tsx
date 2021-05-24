import React, { useContext } from "react";
import { Dialog } from "@material-ui/core";
import { EmailDialogScheduleSendHeader } from "../email-dialog-schedule-send-header/EmailDialogScheduleSendHeader";
import { EmailDialogScheduleSendBody } from "../email-dialog-schedule-send-body/EmailDialogScheduleSendBody";
import { ScheduleDialogContext } from "@s3stores-mail/contexts";

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
        <EmailDialogScheduleSendBody />
      </div>
    </Dialog>
  );
};
