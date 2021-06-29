import React from "react";
import { Grid, Paper } from "@material-ui/core";

export const SceletonEmailListItem: React.FC = () => {
  return (
    <Paper square={true} className={`list-item-wrap `}>
      <Grid container alignItems="center" justify="space-between">
        <Grid xs={2}>
          <div className="sceleton sceleton-email-list-wrap" />
        </Grid>
        <Grid xs={5}>
          <div className="sceleton sceleton-email-list-wrap" />
        </Grid>
        <Grid xs={3}>
          <div className="sceleton sceleton-email-list-wrap" />
        </Grid>
      </Grid>
    </Paper>
  );
};
