import React, { useState } from "react";
import { Dialog } from "@material-ui/core";
import { DialogHeader } from "../dialog/DialogHeader";
import { BillingAddress } from "./BillingAddress";
import { AddBillingAddressForm } from "./AddBillingAddressForm";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { AddCardForm } from "./AddCardForm";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { EditCard } from "./EditCard";

export const CardDialog = ({ handleClose, open, contentType, actionType }) => {
  const [content, setContent] = useState(contentType);

  const onDialogClose = () => {
    handleClose();

    setTimeout(() => {
      setContent(actionType);
    }, 200);
  };

  const showContent = (type) => {
    switch (type) {
      case BillingAddressFormEnum.ADD_ADDRESS: {
        return <AddBillingAddressForm />;
      }
      case BillingAddressFormEnum.ADD_CARD: {
        return <AddCardForm />;
      }
      case BillingAddressFormEnum.LIST_ADDRESS: {
        return <BillingAddress />;
      }
      case BillingAddressFormEnum.EDIT: {
        return <EditCard />;
      }
    }
  };

  return (
    <Dialog
      className="email-send-dialog"
      fullWidth={true}
      onClose={onDialogClose}
      maxWidth="md"
      aria-labelledby="simple-dialog-title"
      open={open}
      PaperProps={{
        style: {
          borderRadius: 0,
          overflowY: "initial",
        },
      }}
    >
      <DialogHeader
        label={`${
          actionType === BillingAddressFormEnum.ADD_ADDRESS
            ? "Add Card"
            : "Edit Card"
        }`}
        onClose={onDialogClose}
      />
      <WalletCardsDialogContext.Provider
        value={{ setContent, actionType, handleClose }}
      >
        {showContent(content)}
      </WalletCardsDialogContext.Provider>
    </Dialog>
  );
};
