import React from "react";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";

export const EmailDialogHOC: (
  component: React.ReactNode,
  dialog: React.ReactNode,
  func?: () => void
) => React.FC = (
  component: React.ReactNode,
  dialog: React.ReactNode,
  func?: () => void
) => {
  return () => {
    const [open, setOpen] = React.useState(false);

    const handleClickOpen = () => {
      setOpen(true);
    };

    const handleClose = () => {
      setOpen(false);
      func && func();
    };

    const sendDialog = {
      open,
      handleClickOpen,
      handleClose,
    };

    return (
      <EmailDialogContext.Provider value={sendDialog}>
        {component}
        {dialog}
      </EmailDialogContext.Provider>
    );
  };
};
