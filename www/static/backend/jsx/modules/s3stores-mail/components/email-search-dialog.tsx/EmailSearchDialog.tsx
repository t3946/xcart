import React from "react";
import { Dialog, DialogContent, DialogTitle } from "@material-ui/core";
import { EmailSearchForm } from "../email-search-form/EmailSearchForm";

export const EmailSearchDialog = ({ open, handleClose }) => {
  return (
    <Dialog
      fullWidth={true}
      onClose={handleClose}
      aria-labelledby="simple-dialog-title"
      open={open}
    >
      <DialogContent>
        <EmailSearchForm />
      </DialogContent>
    </Dialog>
  );
};
