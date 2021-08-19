import React from "react";
import { Button, Grid, Paper, Typography } from "@material-ui/core";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { ResponseForceFraudCheck } from "@admin/modules/order-fraud/ts/types/response";
const api = new ApiService();
interface NotCheckInfo {
  orderId: string | number;
  setNotCheck: (status: boolean) => void;
  handlerFraudInfo: () => void;
  setLoading: (state: boolean) => void;
}
export const NotCheckInfo: React.FC<NotCheckInfo> = ({
  orderId,
  setNotCheck,
  handlerFraudInfo,
  setLoading,
}) => {
  const fraudCheckHandler = () => {
    api
      .get(`/api/order/fraud-check/force-check/${orderId}`)
      .then((res: ResponseForceFraudCheck) => {
        setLoading(true);
        if (res.status) {
          setNotCheck(false);
          handlerFraudInfo();
        }
      });
  };

  return (
    <div className="not-answer-fraud-check">
      <Paper elevation={1}>
        <Grid
          container
          justifyContent="center"
          alignItems="center"
          direction="column"
        >
          <Typography variant="h4" align="center">
            For Basic FC to start C2B payment status must be Authorized or
            Unpaid:PO
          </Typography>
          <Button variant="contained" onClick={fraudCheckHandler}>
            Force Basic Fraud Check
          </Button>
        </Grid>
      </Paper>
    </div>
  );
};
