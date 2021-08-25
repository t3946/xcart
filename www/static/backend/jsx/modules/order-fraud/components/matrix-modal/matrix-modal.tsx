import React, { useContext } from "react";
import { Dialog, DialogContent, DialogContentText } from "@material-ui/core";
import { FraudCheckOrderContext } from "@admin/modules/order-fraud/contexts/FraudCheckOrderContext";
import { DialogTitle } from "@admin/modules/order-fraud/ts/consts/matrix-modal";

export const MatrixModal: React.FC = () => {
  const { dialog, template } = useContext(FraudCheckOrderContext);
  return (
    <Dialog open={dialog.get} onClose={dialog.set}>
      <DialogTitle id="customized-dialog-title" onClose={dialog.set}>
        Detailed information
      </DialogTitle>
      <DialogContent>
        <DialogContentText id="alert-dialog-slide-description">
          <div
            dangerouslySetInnerHTML={{
              __html: template.get.template,
            }}
          />
          <div>
            Outcome: <b>{template.get?.outcome}</b>
          </div>
          <div>
            Weight: <b>{template.get.question_weight}</b>
          </div>
          <div>
            Fraud score subtotal: <b>{template.get.fraud_score}</b>
          </div>
        </DialogContentText>
      </DialogContent>
    </Dialog>
  );
};
