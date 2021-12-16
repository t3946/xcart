import React from "react";

import CloseIcon from "@mui/icons-material/Close";
import Popper from "@mui/material/Popper";
import { IconButton, Paper, Stack, Typography } from "@mui/material";
import { useSelector } from "react-redux";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";
import { convertResult } from "@admin/modules/order-fraud/utils/convert-fraud-score";
interface MatrixModal {
  open: boolean;
  anchor: any;
  handleClose: () => void;
}
export const MatrixModal: React.FC<MatrixModal> = ({
  open,
  anchor,
  handleClose,
}) => {
  const template = useSelector((state: FraudCheckStore) => state.templateView);

  return (
    <Popper open={open} placement={"bottom-start"} anchorEl={anchor}>
      <Paper elevation={4} sx={{ p: 1 }}>
        <Stack direction="row" justifyContent="space-between">
          <Typography sx={{ py: 1 }} variant="h6" align="left">
            Comparing {template.f_fraud_name} to {template.t_fraud_name}
          </Typography>
          <IconButton
            onClick={handleClose}
            color="primary"
            aria-label="upload picture"
            component="span"
          >
            <CloseIcon />
          </IconButton>
        </Stack>

        <div
          dangerouslySetInnerHTML={{
            __html: template.template,
          }}
        />
        <br />
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
            <td>
              {template.type === "address"
                ? `${Math.round(
                    (template.fraud_score / template.question_weight) * 6
                  )}/6`
                : template.outcome}
            </td>
            <td>{template.question_weight}</td>
            <td>{convertResult(template)}</td>
          </tr>
        </table>
      </Paper>
    </Popper>
  );
};
