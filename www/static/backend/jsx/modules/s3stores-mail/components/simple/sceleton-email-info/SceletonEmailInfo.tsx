import React from "react";
import { Grid, Paper } from "@material-ui/core";
import {
  EmailInfoBodyData,
  ReadedSwitch,
} from "@s3stores-mail/components/simple";
import { EmailInfoHeaderIcons } from "@s3stores-mail/components/ordinary/email-info-header-icons/EmailInfoHeaderIcons";
import { IncomingFilesList } from "@s3stores-mail/components/ordinary/incoming-files-list/IncomingFilesList";
import { EmailInfoDataFooter } from "@s3stores-mail/components/smart";

export const SceletonEmailInfo = () => {
  return (
    <div>
      <Paper className="header-wrap info" square={true}>
        <Grid container justify="space-around" alignItems="center">
          <Grid xs={4}>
            <div className="sceleton  sceleton-email-list-wrap" />
          </Grid>
          <Grid xs={3}>
            <div className="sceleton  sceleton-email-list-wrap" />
          </Grid>
          <Grid xs={3}>
            <div className="sceleton  sceleton-email-list-wrap" />
          </Grid>
        </Grid>
      </Paper>
      <Paper elevation={0} square={true}>
        <div className="email-info-data-wrapper">
          <div className="sceleton  sceleton-info-body" />
        </div>
        <div className="email-info-data-wrapper">
          <Grid container justify="space-between" alignItems="center">
            <Grid justify="space-between" container xs={5}>
              <Grid container xs={7}>
                <div className="sceleton  sceleton-info-btn" />
              </Grid>
              <Grid container xs={3}>
                <div className="sceleton  sceleton-info-btn" />
              </Grid>
            </Grid>
            <Grid xs={2}>
              <div className="sceleton  sceleton-info-btn" />
            </Grid>
          </Grid>
        </div>
      </Paper>
    </div>
  );
};
