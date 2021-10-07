import React from "react";
import { Grid } from "@material-ui/core";
import { ColorCreateLabel } from "@s3stores-mail/ts/types/label";
interface ExampleCreateLabel {
  color: ColorCreateLabel;
}
export const ExampleCreateLabel: React.FC<ExampleCreateLabel> = ({ color }) => {
  return (
    <Grid container justifyContent="center" alignItems="center">
      <div
        className="example-create-label-wrapper"
        style={{ backgroundColor: color.background }}
      >
        <span
          className="example-create-label-text"
          style={{ color: color.color }}
        >
          Example label
        </span>
      </div>
    </Grid>
  );
};
