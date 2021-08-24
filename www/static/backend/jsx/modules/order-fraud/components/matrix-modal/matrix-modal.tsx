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
              __html: template.get,
            }}
          />
        </DialogContentText>
      </DialogContent>
    </Dialog>
  );
};
