import React from "react";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import { useSelector } from "react-redux";
import { AccountStore } from "@modules/account/ts/types/store.type";

interface ConfirmDeleteProps {
  onCancelClick: () => void;
  onDeleteClick: () => void;
  deleteType: string;
}

export const ConfirmDelete: React.FC<ConfirmDeleteProps> = ({
  onCancelClick,
  onDeleteClick,
  deleteType,
}) => {
  const loading = useSelector((e: AccountStore) => e.lists.listLoading);
  return (
    <div>
      <p>Are you sure you want to delete this {deleteType}?</p>
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
