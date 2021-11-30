import React from "react";
import { Button } from "@material-ui/core";
import { CardDialog } from "@client/modules/account/components/wallet/CardDialog";
import { useDialog } from "@client/modules/account/hooks/useDialog";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { useHistory } from "react-router";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";

export const AddNewPaymentMethod: React.FC = () => {
  const history = useHistory();

  const addDialog = useDialog();

  const breakpoint = useBreakpoint();

  const addCard = () => {
    breakpoint({
      sm: () => history.push("/account/payments/wallet/add"),
      md: addDialog.handleClickOpen,
    });
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
