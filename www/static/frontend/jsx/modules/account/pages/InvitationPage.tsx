import React from "react";
import { Button } from "@material-ui/core";

export const InvitationPage = () => {
  return (
    <div>
      <div className="page-label">Collaboration invitation</div>
      <div className="page-invitation-subtitle">
        You have been invited to collaborate on "Another TT list" by Roman
        Novokshonov.
      </div>
      <div className="page-invitation-subtitle">
        You will appear to others in the List as
      </div>
      <div className="page-invitation-user-profile-container">
        <div className="d-flex align-content-center">
          <img
            src="/static/frontend/images/pages/account/default-avatar.svg"
            className="page-invitation-user-profile-avatar"
          />
          <div className="page-invitation-user-profile-name d-flex align-content-center">
            Jane
          </div>
        </div>
        <Button
          onClick={null}
          className="account-submit-btn account-submit-btn-outline auto-width-button edit-profile-btn"
        >
          EDIT
        </Button>
      </div>
      <div className="page-invitation-btns">
        <Button className="account-submit-btn auto-width-button accept-list-btn">
          accept and join the list
        </Button>
        <Button
          onClick={null}
          className="account-submit-btn account-submit-btn-outline auto-width-button"
        >
          Cancel
        </Button>
      </div>
    </div>
  );
};
