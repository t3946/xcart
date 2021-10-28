import React from "react";
import { Grid } from "@material-ui/core";
import { Sceleton } from "../../../../modules/shared/components/sceleton/Sceleton";

export const CardSceletonLine: React.FC = () => {
  return (
    <Grid container justifyContent={"space-between"}>
      <Grid item>
        <Sceleton
          margin={"0 0 20px 0"}
          height={153}
          maxWidth={172}
          width={172}
        />
      </Grid>
      <Grid
        className={"sceleton-center-blocks-wrap"}
        justifyContent={"flex-end"}
        container
        xs
      >
        <Sceleton height={25} maxWidth={"100%"} />
        <Sceleton height={35} maxWidth={"100%"} />
        <Sceleton height={35} maxWidth={"100%"} />
        <Sceleton height={7} maxWidth={70} />
      </Grid>
      <Grid xs={2}>
        <Sceleton margin={"0 0 20px 0"} height={70} maxWidth={"100%"} />
        <Sceleton height={40} maxWidth={"100%"} />
      </Grid>
    </Grid>
  );
};
