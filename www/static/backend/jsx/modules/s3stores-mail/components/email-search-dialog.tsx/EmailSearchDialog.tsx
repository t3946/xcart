import React from "react";
import { Dialog, DialogContent } from "@material-ui/core";
import { EmailSearchForm } from "../email-search-form/EmailSearchForm";

interface SearchDialogPropsDto {
  open: boolean;
  handleClose: () => void;
}

export const EmailSearchDialog: React.FC<SearchDialogPropsDto> = ({
  open,
  handleClose,
}) => {
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
