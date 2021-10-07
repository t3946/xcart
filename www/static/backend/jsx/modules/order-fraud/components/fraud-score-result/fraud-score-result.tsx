import React, { useContext } from "react";
import { Grid, Typography } from "@material-ui/core";
import { FraudCheckOrderContext } from "@admin/modules/order-fraud/contexts/FraudCheckOrderContext";

export const FraudScoreResult: React.FC = () => {
  const { settings } = useContext(FraudCheckOrderContext);
  return (
    <Grid
      container
      direction="column"
      justifyContent="center"
      alignItems="flex-end"
    >
      <Typography align="right" variant="body2">
        Bare fraud score: {settings.bare_result}
      </Typography>
      <Typography align="right" variant="body2">
        <b>Effective fraud score: {settings.overall_result}</b>
      </Typography>
      <Typography variant="body2" align="right">
        Current fraud check status: {settings.fraud_status.name}
      </Typography>
    </Grid>
  );
};
