import React from "react";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { AddAddressForm } from "@modules/account/components/addresses/AddAddressForm";

export const AddEditBtnsBlock: React.FC<any> = ({
  handleEdit,
  handleRemove,
  changeDefault,
  defaultItem,
  children,
}) => {
  return (
    <div className="address-footer mt-auto">
      <div className="address-footer-left-part">
        <div onClick={handleEdit} className="address-footer-btn">
          Edit
        </div>
        <div className="address-footer-barrier" />
        <div onClick={handleRemove} className="address-footer-btn">
          Remove
        </div>
      </div>
      {!defaultItem ? (
        <div onClick={changeDefault} className="address-footer-btn">
          Set as Default
        </div>
      ) : (
        children
      )}
    </div>
  );
};
