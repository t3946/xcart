import React, { useContext } from "react";
import { useDispatch } from "react-redux";
import { useHistory } from "react-router-dom";
import { deleteList } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import SubmitCancelButtonsGroup from "@client/modules/account/components/shared/SubmitCancelButtonsGroup";

export const DeleteList = ({ onCancelClick, info }) => {
  const dispatch = useDispatch();
  const { showSnackbar } = useContext(SnackbarContext);
  const history = useHistory();

  const handleDeleteList = () => {
    history.push("/account/your-lists");
    dispatch(deleteList(info.product_list_id, onRequestEnd));
  };
  const onRequestEnd = () => {
    showSnackbar({
      header: "Success",
      message: `${info.name} list deleted successfully`,
      theme: "success",
    });
  };
  return (
    <div>
      <p>Are you sure you want to delete this list?</p>
      <SubmitCancelButtonsGroup
        submitText="Confirm"
        cancelText="Cancel"
        onCancel={onCancelClick}
        groupAdvancedClasses={"manage-list-btns"}
        onConfirm={handleDeleteList}
      />
    </div>
  );
};
