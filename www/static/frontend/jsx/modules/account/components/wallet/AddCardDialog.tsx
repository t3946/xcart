import React, { useContext, useState } from "react";
import { Dialog } from "@material-ui/core";
import { DialogHeader } from "../dialog/DialogHeader";
import { BillingAddress } from "./BillingAddress";
import { AddBillingAddressForm } from "./AddBillingAddressForm";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { AddCardForm } from "./AddCardForm";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";

export const AddCardDialog = ({ handleClose, open, contentType }) => {
  const [content, setContent] = useState(contentType);

  const onDialogClose = () => {
    handleClose();
    setContent(BillingAddressFormEnum.ADD_CARD);
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
      <DialogHeader label="Add Card" onClose={onDialogClose} />
      <WalletCardsDialogContext.Provider value={{ setContent }}>
        {showContent(content)}
      </WalletCardsDialogContext.Provider>
    </Dialog>
  );
};
