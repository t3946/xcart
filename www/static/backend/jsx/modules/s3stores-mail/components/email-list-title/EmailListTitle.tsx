import React, { useContext } from "react";
import { Button, Grid } from "@material-ui/core";
import AddIcon from "@material-ui/icons/Add";
import { EmailSendDialogContext } from "@s3stores-mail/contexts";

export const EmailListTitle: React.FC = () => {
  const dialog = useContext(EmailSendDialogContext);

  return (
    <Grid className="list-title-wrap" container justify={"space-between"}>
      <Grid xs={2}>
        <Button
          onClick={dialog.handleClickOpen}
          className="title-button"
          variant="outlined"
        >
          Compose
          <AddIcon className="title-button-icon" />
        </Button>
      </Grid>
      <Grid container alignItems={"center"} xs={7}>
        <span className="title-text">Inbox / Sorting dashboard</span>
      </Grid>
    </Grid>
  );
};
