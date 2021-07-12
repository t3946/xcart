import React from "react";
import { AddressDialogContext } from "../contexts/address-dialog-context/AddressDialogContext";

export const AddressDialogHOC = (
  component: React.ReactNode,
  dialog: React.ReactNode,
  func?: () => void
): React.FC => {
  return () => {
    const [open, setOpen] = React.useState(false);

    const handleClickOpen = () => {
      setOpen(true);
    };

    const handleClose = () => {
      setOpen(false);
      func && func();
    };

    const dialog = {
      open,
      handleClickOpen,
      handleClose,
    };

    return (
      <AddressDialogContext.Provider value={dialog}>
        {component}
        {dialog}
      </AddressDialogContext.Provider>
    );
  };
};
