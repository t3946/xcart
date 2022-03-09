import React from "react";
import { CreateNewList } from "@modules/account/components/lists/CreateNewList";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";

interface CreateNewListDialogProps {
  handleClose: () => void;
  open: boolean;
  productId?: string;
  onProductAdded?: (listInfo: any) => void;
  actionType?: "product" | "list";
}

export const CreateNewListDialog: React.FC<CreateNewListDialogProps> = ({
  handleClose,
  open,
  productId,
  onProductAdded,
  actionType,
}) => {
  return (
    <BootstrapDialogHOC
      show={open}
      title={"Create a new list"}
      onClose={handleClose}
    >
      <CreateNewList
        productId={productId}
        onCreateList={onProductAdded}
        onCancelBtnClick={handleClose}
        actionType={actionType}
      />
    </BootstrapDialogHOC>
  );
};
