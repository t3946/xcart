import React from "react";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import AddCardForm from "@modules/account/components/wallet/AddCardForm";

interface IProps {
  handleClose: () => void;
  open: boolean;
}

const CardDialog: React.FC<IProps> = (props) => {
  const { handleClose, open } = props;

  return (
    <BootstrapDialogHOC
      onClose={handleClose}
      show={open}
      title={`Add Card`}
      classes={{ modal: "payment-method__modal" }}
    >
      <AddCardForm />
    </BootstrapDialogHOC>
  );
};

export default CardDialog;
