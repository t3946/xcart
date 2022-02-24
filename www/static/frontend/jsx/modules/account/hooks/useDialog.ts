import React from "react";

interface DialogReturnData {
  open: boolean;
  handleClickOpen: () => void;
  handleClose: () => void;
}

export function useDialog(func?: () => void): DialogReturnData {
  const [open, setOpen] = React.useState(false);

  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
    func?.();
  };

  return {
    open,
    handleClickOpen,
    handleClose,
  };
}
