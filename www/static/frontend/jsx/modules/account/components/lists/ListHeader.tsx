import React from "react";
import ShareIcon from "@material-ui/icons/Share";

export const ListHeader = () => {
  return (
    <div className="list-header-container">
      <div className="list-header-left-side">
        <div className="list-header-name">Addresses</div>
        <div className="list-header-actions">
          <div className="list-header-action-item blue">Manage List</div>
          <div className="list-header-action-item red">Delete List</div>
        </div>
      </div>

      <div className="list-header-shared-block">
        <ShareIcon className="list-header-share-btn blue" />
        <div className="list-header-share-text blue">
          Share list with others
        </div>
      </div>
    </div>
  );
};
