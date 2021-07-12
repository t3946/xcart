import React, { useContext } from "react";
import { AddressDialogContext } from "../../contexts/address-dialog-context/AddressDialogContext";

export const AddNewAddress = () => {
  const dialog = useContext(AddressDialogContext);
  return (
    <div className="add-address address-container">
      <div onClick={() => dialog.handleClickOpen()}>
        <img src="/static/frontend/images/icons/account/plus.svg" />
      </div>
      <div className="add-address-label">Add new address</div>
    </div>
  );
};
