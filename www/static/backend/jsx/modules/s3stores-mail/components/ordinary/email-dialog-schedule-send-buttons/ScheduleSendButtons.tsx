import React, { useContext } from "react";
import { Button, Grid } from "@material-ui/core";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";
import { EmailSendBodyContext } from "@s3stores-mail/contexts";

export const ScheduleSendButtons: React.FC<any> = ({ sendData }) => {
  const dialog = useContext(EmailDialogContext);

  const { sendMessage } = useContext(EmailSendBodyContext);
  return (
    <Grid className="schedule-send-buttons" container justify="space-between">
      <Button
        disabled={!sendData.date}
        onClick={() => {
          dialog.handleClose();
          sendMessage(sendData, "Email has been scheduled to be sent");
        }}
        className="schedule-send-buttons-send"
      >
        SCHEDULE SEND
      </Button>
      <Button
        className="schedule-send-buttons-cancel"
        onClick={dialog.handleClose}
      >
        CANCEL
      </Button>
    </Grid>
  );
};
