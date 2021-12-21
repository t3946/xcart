import React from "react";
import { CardDialog } from "@modules/account/components/wallet/CardDialog";
import { useDialog } from "@modules/account/hooks/useDialog";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { useHistory } from "react-router";
import { useRouter } from "next/router";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";

export const AddNewPaymentMethod: React.FC = () => {
  const router = useRouter();

  const addDialog = useDialog();

  const breakpoint = useBreakpoint();

  const addCard = () => {
    breakpoint({
      sm: () => router.push("/account/payments/wallet/add"),
      md: addDialog.handleClickOpen,
    });
  };

  return (
    <div className="add-new-payment-method-container">
      <button
        onClick={addCard}
        className="form-button account-submit-btn edit-card-btn add-new-payment"
      >
        Add a credit or debit card
      </button>
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
