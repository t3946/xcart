import React from "react";
import {
  Button,
  FormControl,
  Grid,
  Input,
  InputLabel,
  MenuItem,
  Paper,
  Select,
} from "@material-ui/core";
import ReplyIcon from "@material-ui/icons/Reply";
import ForwardIcon from "@material-ui/icons/Forward";
import { EmailSend } from "../email-send/EmailSend";
import { EmailSelectInfo } from "../email-select-info/EmailSelectInfo";

export const EmailInfoData = ({ data }) => {
  const [open, setOpen] = React.useState(false);

  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
  };

  return (
    <div>
      <Paper elevation={0} square={true} className="email-info-data-wrapper">
        <Grid container justify="space-between">
          <Grid className="email-title-wrap">
            <Grid container>
              <span className="email-info-from">from:</span>
              <span className="email-info-title-text">
                FAXAGE support@faxage.com
              </span>
            </Grid>
            <Grid container>
              <span className="email-info-to">To:</span>
              <span className="email-info-title-text">
                faxage800@s3stores.com reply-to: support@faxage.com
              </span>
            </Grid>
          </Grid>
          <Grid>
            <Grid container>
              <span className="email-info-from">from:</span>
              <span className="email-info-title-text">
                FAXAGE support@faxage.com
              </span>
            </Grid>
          </Grid>
        </Grid>

        <span className="email-info-text">
          You have received a new 2 page fax on FAXAGE from (707)792-1362. A
          copy is attached for your reference. You may also visit
          http://www.faxage. com to log in and work with your faxes.
        </span>
        <Grid container alignItems="center" className="email-info-footer">
          <Grid xs={2}>
            <Button className="email-info-btn" variant="outlined">
              <ReplyIcon className="email-info-btn-icon-reply" />
              <span>REPLY</span>
            </Button>
          </Grid>
          <Grid container alignItems="center" xs={3}>
            <EmailSelectInfo />
          </Grid>
          <Grid container alignItems="center" xs={7} justify="flex-end">
            <Button
              onClick={handleClickOpen}
              className="email-info-btn"
              variant="outlined"
            >
              <ForwardIcon className="email-info-btn-icon" />
              <span>FORWARD</span>
            </Button>
          </Grid>
        </Grid>
      </Paper>
      <EmailSend open={open} handleClose={handleClose} />
    </div>
  );
};
