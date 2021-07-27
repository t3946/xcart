import React from "react";

export function useDialog(func?) {
  const [open, setOpen] = React.useState(false);

  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
    func && func();
  };

  return {
    open,
    handleClickOpen,
    handleClose,
  };
}
