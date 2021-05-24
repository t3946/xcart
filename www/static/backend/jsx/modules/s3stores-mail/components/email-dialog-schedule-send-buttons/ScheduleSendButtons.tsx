import React, { useContext } from "react";
import { Button, Grid } from "@material-ui/core";
import { ScheduleDialogContext } from "@s3stores-mail/contexts";

export const ScheduleSendButtons: React.FC = () => {
  const dialog = useContext(ScheduleDialogContext);
  return (
    <Grid className="schedule-send-buttons" container justify="space-between">
      <Button className="schedule-send-buttons-send">SCHEDULE SEND</Button>
      <Button
        className="schedule-send-buttons-cancel"
        onClick={dialog.handleClose}
      >
        CANCEL
      </Button>
    </Grid>
  );
};
