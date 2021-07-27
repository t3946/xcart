import React from "react";
import { Dialog } from "@material-ui/core";
import { DialogHeader } from "../dialog/DialogHeader";
import { BillingAddress } from "./BillingAddress";

export const AddCardDialog = ({ handleClose, open }) => {
  return (
    <Dialog
      className="email-send-dialog"
      fullWidth={true}
      onClose={handleClose}
      maxWidth="md"
      aria-labelledby="simple-dialog-title"
      open={open}
      PaperProps={{
        style: {
          borderRadius: 0,
        },
      }}
    >
      <DialogHeader label="Add Card" onClose={handleClose} />
      <BillingAddress />
    </Dialog>
  );
};
