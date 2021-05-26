import React from "react";
import { Grid, Paper } from "@material-ui/core";
import { ReadedSwitch } from "@s3stores-mail/components/simple/readed-switch/ReadedSwitch";
import { IconsList } from "@s3stores-mail/components/simple/icons-list/IconsList";

export const EmailInfoHeader: React.FC<any> = ({ info }) => {
  return (
    <Paper className="header-wrap info" square={true}>
      <Grid container justify="space-around" alignItems="center">
        <Grid xs={6}>
          <span>{info.title}</span>
        </Grid>
        <Grid>
          <ReadedSwitch editAction={null} readed={info.read} />
        </Grid>
        <Grid>
          <IconsList />
        </Grid>
      </Grid>
    </Paper>
  );
};
