import React from "react";
import { Grid, Paper } from "@material-ui/core";

export const SceletonItem = () => {
  return (
    <Paper square={true} className={`list-item-wrap `}>
      <Grid container alignItems="center" justify="space-between">
        <Grid xs={2}>
          <div className="sceleton" />
        </Grid>
        <Grid xs={5}>
          <div className="sceleton" />
        </Grid>
        <Grid xs={3}>
          <div className="sceleton" />
        </Grid>
      </Grid>
    </Paper>
  );
};
