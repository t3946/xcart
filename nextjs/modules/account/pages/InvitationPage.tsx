import React from "react";
import { Button } from "@material-ui/core";
import { useDispatch, useSelector } from "react-redux";
import { acceptInvite } from "@redux/actions/account-actions/ListsActions";
import { useHistory } from "react-router";
import StoreInterface from "@modules/account/ts/types/store.type";
import { viewUserListRight } from "@modules/account/utils/view-user-list-right";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";

export const InvitationPage: React.FC = () => {
  const dispatch = useDispatch();

  const history = useHistory();

  const appDataWindow: any = window;

  const onAccepted = () => {
    history.push("/account/your-lists");
  };

  const editProfile = () => {
    history.push("/account/public-profile");
  };

  const onCancelClick = () => {
    history.push("/account/");
  };

  const user = useSelector((e: StoreInterface) => e.user);

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
      <MobileMenuBackBtn redirectUrl={`/account`} label={"account"} />
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
        <div className="page-invitation-user-profile">
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
      <SubmitCancelButtonsGroup
        submitText="accept and join the list"
        cancelText="Cancel"
        onCancel={onCancelClick}
        groupAdvancedClasses={["accept-invite-btn-group"]}
        cancelAdvancedClasses={"accept-invite-btn-group-cancel"}
        onConfirm={onAcceptClick}
        disabled={loading}
      />
    </div>
  );
};
