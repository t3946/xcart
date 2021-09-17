import React from "react";
import { Button } from "@material-ui/core";
import { useDispatch, useSelector } from "react-redux";
import { acceptInvite } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { useHistory } from "react-router";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { viewUserListRight } from "@client/modules/account/utils/view-user-list-right";

export const InvitationPage = () => {
  const dispatch = useDispatch();

  const history = useHistory();

  const appDataWindow: any = window;

  const onAccepted = () => {
    history.push("/account/your-lists");
  };

  const editProfile = () => {
    history.push("/account/public-profile");
  };

  const user = useSelector((e: AccountStore) => e.user);

  const loading = useSelector((store: AccountStore) => store.lists.listLoading);

  const onAcceptClick = () => {
    dispatch(
      acceptInvite(
        appDataWindow.appData.invite_data.inviteList.product_list_id,
        appDataWindow.appData.invite_data.type,
        onAccepted
      )
    );
  };

  return (
    <div>
      <div className="page-label">Collaboration invitation</div>
      <div className="page-invitation-subtitle">
        You have been invited to collaborate on "
        {appDataWindow.appData.invite_data.inviteList.name}" by{" "}
        {appDataWindow.appData.invite_data.userName}
      </div>
      <div className="page-invitation-subtitle">
        You will appear to others in the List as{" "}
        <b>{viewUserListRight(appDataWindow.appData.invite_data.type)}</b>
      </div>
      <div className="page-invitation-user-profile-container">
        <div className="d-flex align-content-center">
          <img
            src="/static/frontend/images/pages/account/default-avatar.svg"
            className="page-invitation-user-profile-avatar"
          />
          <div className="page-invitation-user-profile-name d-flex align-content-center">
            {user.name}
          </div>
        </div>
        <Button
          onClick={editProfile}
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
          onClick={editProfile}
          className="account-submit-btn account-submit-btn-outline auto-width-button"
        >
          Cancel
        </Button>
      </div>
    </div>
  );
};
