import React from "react";
import { Button, Grid, Paper } from "@material-ui/core";
import { useDispatch } from "react-redux";
import { fetchForceFraudCheck } from "@redux/actions/fraudCheckActions";
interface NotCheckInfo {
  orderId: number;
  handleForceCheck: () => void;
}
export const NotCheckInfo: React.FC<NotCheckInfo> = ({
  orderId,
  handleForceCheck,
}) => {
  const dispatch = useDispatch();

  return (
    <div className="not-answer-fraud-check">
      <Paper elevation={1} className="paper-wrapper">
        <Grid
          container
          justifyContent="center"
          alignItems="center"
          direction="column"
        >
          <span className="not-check-info-title">
            For the basic Fraud Check to start <b>C2B payment status</b> must be
            'Authorized' or 'Unpaid: PO'
          </span>
          <div className="fraud-force-check-button">
            <Button variant="contained" onClick={handleForceCheck}>
              Force Basic Fraud Check
            </Button>
          </div>
        </Grid>
      </Paper>
    </div>
  );
};
