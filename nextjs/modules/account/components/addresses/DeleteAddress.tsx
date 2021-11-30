import React from "react";
import { useSelector } from "react-redux";
import { AccountStore } from "@modules/account/ts/types/store.type";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";

interface DeleteAddressProps {
  onCancelClick: () => void;
  onDeleteClick: () => void;
}

export const DeleteAddress: React.FC<DeleteAddressProps> = ({
  onCancelClick,
  onDeleteClick,
}) => {
  const loading = useSelector((e: AccountStore) => e.addresses.loading);
  return (
    <div>
      <p>Are you sure you want to delete this address</p>
      <SubmitCancelButtonsGroup
        submitText="Confirm"
        cancelText="Cancel"
        disabled={loading}
        onCancel={onCancelClick}
        groupAdvancedClasses={"manage-list-btns"}
        onConfirm={onDeleteClick}
      />
    </div>
  );
};
