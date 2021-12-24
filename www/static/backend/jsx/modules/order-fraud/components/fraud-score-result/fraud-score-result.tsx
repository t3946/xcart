import React, { Fragment } from "react";
import { Grid, Typography } from "@mui/material";
import { useSelector } from "react-redux";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";

export const FraudScoreResult: React.FC = () => {
  const orderInfo = useSelector(
    (state: FraudCheckStore) => state.data.orderInfo
  );
  return (
    // <Grid
    //   container
    //   direction="column"
    //   justifyContent="flex-start"
    //   alignItems="flex-start"
    // >
    <Fragment>
      <Typography align="left" variant="body2">
        <b>Fraud Score:</b> {orderInfo.bareResult.toFixed(2)}
      </Typography>
      <Typography variant="body2" align="left">
        <b>Current fraud check status:</b> {orderInfo.fraudStatus.name}
      </Typography>
    </Fragment>
  );
};
// TODO: orderInfo.overallResult убрать!!!
