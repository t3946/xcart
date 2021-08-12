import React from "react";
import { Button } from "@material-ui/core";
import { CardDialog } from "./CardDialog";
import { useDialog } from "../../hooks/useDialog";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { useHistory } from "react-router";
import { accountStore } from "../../../../redux/stores/StoreAccount";

export const AddNewPaymentMethod: React.FC = () => {
  const history = useHistory();

  const addDialog = useDialog();

  const addCard = () => {
    if (accountStore.getState().main.breakpoint.is768) {
      history.push("/account/payments/wallet/add");
      return;
    }
    addDialog.handleClickOpen();
  };

  return (
    <div className="add-new-payment-method-container">
      <Button
        onClick={addCard}
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
