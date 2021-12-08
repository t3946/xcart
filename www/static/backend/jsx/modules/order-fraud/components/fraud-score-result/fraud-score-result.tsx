import React from "react";
import { Grid, Typography } from "@mui/material";
import { useSelector } from "react-redux";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";

export const FraudScoreResult: React.FC = () => {
  const orderInfo = useSelector(
    (state: FraudCheckStore) => state.data.orderInfo
  );
  return (
    <Grid
      container
      direction="column"
      justifyContent="center"
      alignItems="flex-end"
    >
      <Typography align="right" variant="body2">
        <b>Bare fraud score:</b> {orderInfo.bareResult}
      </Typography>
      <Typography variant="body2" align="right">
        <b>Current fraud check status:</b> {orderInfo.fraudStatus.name}
      </Typography>
    </Grid>
  );
};
// TODO: orderInfo.overallResult убрать!!!
