import React from "react";
import { Button } from "@material-ui/core";

export const DeleteList = ({ onCancelClick, confirmDelete }) => {
  return (
    <div>
      <p>Are you sure you want to delete this list?</p>
      <div className="edit-idea-btns">
        <Button
          onClick={confirmDelete}
          className="account-submit-btn auto-width-button cancel-edit-card-btn"
        >
          Confirm
        </Button>
        <Button
          onClick={onCancelClick}
          className="account-submit-btn account-submit-btn-outline auto-width-button "
        >
          Cancel
        </Button>
      </div>
    </div>
  );
};
