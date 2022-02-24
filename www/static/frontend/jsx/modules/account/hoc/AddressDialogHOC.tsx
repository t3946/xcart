import React from "react";
import { AddressDialogContext } from "../contexts/AddressDialogContext";

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

    return (
      <AddressDialogContext.Provider
        value={{ open, handleClickOpen, handleClose }}
      >
        {component}
        {dialog}
      </AddressDialogContext.Provider>
    );
  };
};
