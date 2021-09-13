import React from "react";
import { Button } from "@material-ui/core";
import { useDispatch, useSelector } from "react-redux";
import { acceptInvite } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { useHistory } from "react-router";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";

export const InvitationPage = () => {
  const dispatch = useDispatch();

  const history = useHistory();

  const appDataWindow: any = window;

  const onAccepted = () => {
    history.push("/account/your-lists");
  };

  const loading = useSelector((store: AccountStore) => store.lists.listLoading);

  const onAcceptClick = () => {
    console.log(appDataWindow.appData);
    dispatch(
      acceptInvite(appDataWindow.appData.invite_data.listId, onAccepted)
    );
  };

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
          disabled={loading}
          className="account-submit-btn account-submit-btn-outline auto-width-button edit-profile-btn"
        >
          EDIT
        </Button>
      </div>
      <div className="page-invitation-btns">
        <Button
          onClick={onAcceptClick}
          disabled={loading}
          className="account-submit-btn auto-width-button accept-list-btn"
        >
          accept and join the list
        </Button>
        <Button
          disabled={loading}
          onClick={null}
          className="account-submit-btn account-submit-btn-outline auto-width-button"
        >
          Cancel
        </Button>
      </div>
    </div>
  );
};
