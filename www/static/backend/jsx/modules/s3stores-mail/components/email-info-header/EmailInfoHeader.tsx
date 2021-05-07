import React from "react";
import { Grid, Paper } from "@material-ui/core";
import { IconsList } from "../icons-list/IconsList";
import { ReadedSwitch } from "../readed-switch/ReadedSwitch";

export const EmailInfoHeader = ({ info }) => {
  return (
    <Paper className="header-wrap info" square={true}>
      <Grid container justify="space-around" alignItems="center">
        <Grid xs={6}>
          <span>{info.title}</span>
        </Grid>
        <Grid>
          <ReadedSwitch readed={info.read} />
        </Grid>
        <Grid>
          <IconsList />
        </Grid>
      </Grid>
    </Paper>
  );
};
