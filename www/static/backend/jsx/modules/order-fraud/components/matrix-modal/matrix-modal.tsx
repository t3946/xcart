import React, { useContext } from "react";
import {
  Dialog,
  DialogContent,
  DialogContentText,
  DialogTitle,
  Typography,
} from "@material-ui/core";
import { FraudCheckOrderContext } from "@admin/modules/order-fraud/contexts/FraudCheckOrderContext";

export const MatrixModal: React.FC = () => {
  const { dialog, template } = useContext(FraudCheckOrderContext);
  return (
    <Dialog open={dialog.get} onClose={dialog.set}>
      <DialogTitle id="alert-dialog-slide-title">
        <Typography align="center" variant="h6">
          Info question
        </Typography>
      </DialogTitle>
      <DialogContent>
        <DialogContentText id="alert-dialog-slide-description">
          <div
            dangerouslySetInnerHTML={{
              __html: template.get,
            }}
          />
        </DialogContentText>
      </DialogContent>
    </Dialog>
  );
};
