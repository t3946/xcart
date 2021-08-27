import React from "react";
import { Dialog } from "@material-ui/core";
import { DialogHeader } from "@client/modules/account/components/dialog/DialogHeader";
import { CreateNewList } from "@client/modules/account/components/lists/CreateNewList";

export const CreateNewListDialog = ({ handleClose, open }) => {
  return (
    <Dialog
      className="email-send-dialog"
      fullWidth={true}
      onClose={handleClose}
      maxWidth="sm"
      aria-labelledby="simple-dialog-title"
      open={open}
      PaperProps={{
        style: {
          borderRadius: 0,
          overflowY: "initial",
        },
      }}
    >
      <DialogHeader label={`Create a new list`} onClose={handleClose} />
      <CreateNewList onCancelBtnClick={handleClose} />
    </Dialog>
  );
};
