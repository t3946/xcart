import React from "react";
import { Grid } from "@material-ui/core";
import { Sceleton } from "../../../../modules/shared/components/sceleton/Sceleton";

interface CardSceletonBlockPropsDto {
  buttons?: boolean;
}

export const CardSceletonBlock: React.FC<CardSceletonBlockPropsDto> = ({
  buttons = true,
}) => {
  return (
    <div className="sceleton-block-wrapper">
      <Sceleton margin={"0 0 10px 0"} height={172} maxWidth={"100%"} />
      <Sceleton margin={"0 0 5px 0"} height={40} maxWidth={"100%"} />
      <Sceleton margin={"0 0 5px 0"} height={15} maxWidth={"100%"} />
      <Grid justify={"space-between"} container>
        {buttons && (
          <React.Fragment>
            <Sceleton height={35} maxWidth={"50%"} />
            <Sceleton height={35} maxWidth={"45%"} />
          </React.Fragment>
        )}
      </Grid>
    </div>
  );
};
