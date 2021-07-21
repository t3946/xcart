import React, { useContext } from "react";
import { AddressDialogContext } from "../../contexts/AddressDialogContext";

export const AddNewAddress = () => {
  const dialog = useContext(AddressDialogContext);
  return (
    <div
      onClick={() => dialog.handleClickOpen()}
      className="add-address address-container"
    >
      <div>
        <img src="/static/frontend/images/icons/account/plus.svg" />
      </div>
      <div className="add-address-label">Add new address</div>
    </div>
  );
};
