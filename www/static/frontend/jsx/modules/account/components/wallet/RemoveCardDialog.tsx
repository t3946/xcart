import React from "react";
import { Dialog } from "@material-ui/core";
import { DialogHeader } from "../dialog/DialogHeader";
import { RemoveCard } from "./RemoveCard";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";

export const RemoveCardDialog = ({ handleClose, open, cardInfo }) => {
  return (
    <Dialog
      className="email-send-dialog"
      fullWidth={true}
      onClose={handleClose}
      aria-labelledby="simple-dialog-title"
      open={open}
      maxWidth="md"
      PaperProps={{
        style: {
          borderRadius: 0,
        },
      }}
    >
      <DialogHeader label="Remove card" onClose={handleClose} />
      <WalletCardsDialogContext.Provider
        value={{
          handleClose,
        }}
      >
        <RemoveCard cardInfo={cardInfo} />
      </WalletCardsDialogContext.Provider>
    </Dialog>
  );
};
