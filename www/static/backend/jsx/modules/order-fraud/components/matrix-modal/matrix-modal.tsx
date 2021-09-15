import React, { useContext } from "react";
import { Dialog, DialogContent, DialogContentText } from "@material-ui/core";
import { FraudCheckOrderContext } from "@admin/modules/order-fraud/contexts/FraudCheckOrderContext";
import { DialogTitle } from "@admin/modules/order-fraud/ts/consts/matrix-modal";

export const MatrixModal: React.FC = () => {
  const { dialog, template } = useContext(FraudCheckOrderContext);
  return (
    <Dialog open={dialog.get} onClose={dialog.set}>
      <DialogTitle id="customized-dialog-title" onClose={dialog.set}>
        Comparing {template.get.f_fraud_name} to {template.get.t_fraud_name}
      </DialogTitle>
      <DialogContent>
        <DialogContentText id="alert-dialog-slide-description">
          <div
            dangerouslySetInnerHTML={{
              __html: template.get.template,
            }}
          />
          <table className="table-matrix-modal-info" border={1}>
            <tr>
              <th>Outcome</th>
              <th>Weight</th>
              <th>
                Fraud score
                <br /> subtotal
              </th>
            </tr>
            <tr>
              <td>{template.get?.outcome}</td>
              <td>{template.get.question_weight}</td>
              <td>{template.get.fraud_score}</td>
            </tr>
          </table>
        </DialogContentText>
      </DialogContent>
    </Dialog>
  );
};
