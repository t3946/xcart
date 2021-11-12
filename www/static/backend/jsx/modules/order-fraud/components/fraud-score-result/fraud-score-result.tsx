import React from "react";
import { Grid, Typography } from "@material-ui/core";
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
        Bare fraud score: {orderInfo.bareResult}
      </Typography>
      <Typography align="right" variant="body2">
        <b>Effective fraud score: {orderInfo.overallResult}</b>
      </Typography>
      <Typography variant="body2" align="right">
        Current fraud check status: {orderInfo.fraudStatus.name}
      </Typography>
    </Grid>
  );
};
