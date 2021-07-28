import React from "react";
import { Button } from "@material-ui/core";
import { CardDialog } from "./CardDialog";
import { useDialog } from "../../hooks/useDialog";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";

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
      <CardDialog
        contentType={BillingAddressFormEnum.ADD_CARD}
        actionType={BillingAddressFormEnum.ADD_CARD}
        open={addDialog.open}
        handleClose={addDialog.handleClose}
      />
    </div>
  );
};
