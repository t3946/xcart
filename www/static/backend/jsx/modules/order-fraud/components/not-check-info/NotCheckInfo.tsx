import React from "react";
import { Grid, Paper } from "@mui/material";
import { Button } from "react-bootstrap";
interface NotCheckInfo {
  handleForceCheck: () => void;
}
export const NotCheckInfo: React.FC<NotCheckInfo> = ({ handleForceCheck }) => {
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
            <Button onClick={handleForceCheck} variant="secondary">
              Force Basic Fraud Check
            </Button>
          </div>
        </Grid>
      </Paper>
    </div>
  );
};
