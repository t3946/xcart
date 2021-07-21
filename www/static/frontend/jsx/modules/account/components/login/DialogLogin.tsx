import React, { useContext } from "react";
import { Dialog } from "@material-ui/core";
import { AddressDialogContext } from "@modules/account/contexts/AddressDialogContext";

export const DialogLogin = () => {
  const dialog = useContext(AddressDialogContext);
  return (
    <Dialog
      maxWidth={"sm"}
      className="email-send-dialog"
      fullWidth={true}
      onClose={dialog.handleClose}
      aria-labelledby="simple-dialog-title"
      open={dialog.open}
      PaperProps={{
        style: {
          borderRadius: 0,
        },
      }}
    >
      Sing In
    </Dialog>
  );
};
