import React, { useContext } from "react";
import { Dialog, DialogContent } from "@material-ui/core";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";
import { EmailSearchDialogContainer } from "@s3stores-mail/containers/email-search-dialog/EmailSearchDialog.container";

export const EmailSearchDialog: React.FC = () => {
  const { open, handleClose } = useContext(EmailDialogContext);
  return (
    <Dialog
      fullWidth={true}
      onClose={handleClose}
      aria-labelledby="simple-dialog-title"
      open={open}
    >
      <DialogContent>
        <EmailSearchDialogContainer />
      </DialogContent>
    </Dialog>
  );
};
