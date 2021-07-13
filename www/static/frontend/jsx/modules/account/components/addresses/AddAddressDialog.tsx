import React, { useContext } from "react";
import { Dialog } from "@material-ui/core";
import { AddressDialogContext } from "../../contexts/AddressDialogContext";
import { DialogHeader } from "../dialog/DialogHeader";

export const AddAddressDialog = () => {
  const dialog = useContext(AddressDialogContext);
  return (
    <Dialog
      maxWidth={"md"}
      className="email-send-dialog"
      fullWidth={true}
      onClose={dialog.handleClose}
      aria-labelledby="simple-dialog-title"
      open={dialog.open}
      PaperProps={{
        style: {
          borderRadius: 0,
          minHeight: 650,
        },
      }}
    >
      <DialogHeader label="Add address" onClose={dialog.handleClose} />
      123213123
    </Dialog>
  );
};
