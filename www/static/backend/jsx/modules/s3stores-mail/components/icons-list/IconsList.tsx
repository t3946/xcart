import React from "react";
import { ForwardIc } from "../icons/Forward";
import { Grid } from "@material-ui/core";
import { PrintIc } from "../icons/Print";
import { ReplyIc } from "../icons/Reply";
import { StarIc } from "../icons/Star";
import { EmailIc } from "../icons/Email";

export const IconsList = () => {
  return (
    <Grid>
      <EmailIc />
      <ForwardIc />
      <PrintIc />
      <ReplyIc />
      <StarIc />
    </Grid>
  );
};
