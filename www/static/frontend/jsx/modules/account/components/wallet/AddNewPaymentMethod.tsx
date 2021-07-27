import React from "react";
import { Button } from "@material-ui/core";
import { AddCardDialog } from "./AddCardDialog";
import { useDialog } from "../../hooks/useDialog";

export const AddNewPaymentMethod = () => {
  const addDialog = useDialog();
  return (
    <div className="add-new-payment-method-container">
      <Button
        onClick={addDialog.handleClickOpen}
        className="account-submit-btn edit-card-btn add-new-payment"
      >
        Add a credit or debit card
      </Button>
      <div>S3 Stores Inc accepts major credit and debit cards</div>
      <AddCardDialog
        open={addDialog.open}
        handleClose={addDialog.handleClose}
      />
    </div>
  );
};
