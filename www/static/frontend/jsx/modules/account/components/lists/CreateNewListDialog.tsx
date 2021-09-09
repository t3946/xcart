import React from "react";
import { CreateNewList } from "@client/modules/account/components/lists/CreateNewList";
import BootstrapDialogHOC from "@client/modules/account/hoc/BootstrapDialogHOC";

export const CreateNewListDialog = ({ handleClose, open }) => {
  return (
    <BootstrapDialogHOC
      show={open}
      title={"Create a new list"}
      onClose={handleClose}
    >
      <CreateNewList onCancelBtnClick={handleClose} />
    </BootstrapDialogHOC>
  );
};
