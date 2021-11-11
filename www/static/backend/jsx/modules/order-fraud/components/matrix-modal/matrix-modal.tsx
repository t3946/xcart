import React from "react";

import CloseIcon from "@mui/icons-material/Close";
import Popper, { PopperPlacementType } from "@mui/material/Popper";
import { IconButton, Paper, Stack, Typography } from "@mui/material";
import { useSelector } from "react-redux";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";
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
  console.log(template);
  console.log("OPEN", open);
  return (
    <Popper open={open} placement={"right-start"} anchorEl={anchor}>
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
            <td>{template.outcome}/6</td>
            <td>{template.question_weight}</td>
            <td>{Math.round(template.fraud_score)}</td>
          </tr>
        </table>
      </Paper>
      {/*<DialogContent>*/}
      {/*  <DialogContentText id="alert-dialog-slide-description">*/}
      {/*    <div*/}
      {/*      dangerouslySetInnerHTML={{*/}
      {/*        __html: template.template,*/}
      {/*      }}*/}
      {/*    />*/}

      {/*  </DialogContentText>*/}
      {/*</DialogContent>*/}
    </Popper>
  );
};
