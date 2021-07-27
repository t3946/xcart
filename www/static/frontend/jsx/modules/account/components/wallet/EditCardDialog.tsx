import React from "react";
import { Dialog } from "@material-ui/core";
import { DialogHeader } from "../dialog/DialogHeader";

export const EditCardDialog = ({ handleClose, open }) => {
  return (
    <Dialog
      className="email-send-dialog"
      fullWidth={true}
      onClose={handleClose}
      aria-labelledby="simple-dialog-title"
      open={open}
      PaperProps={{
        style: {
          borderRadius: 0,
        },
      }}
    >
      <DialogHeader label="Edit card" onClose={handleClose} />
      Edit Card
    </Dialog>
  );
};
