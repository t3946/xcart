import React from "react";
import { Button } from "@material-ui/core";
import { viewUserListRight } from "@modules/account/utils/view-user-list-right";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import getStoreUrl from "@utils/getStoreUrl";
import { inviteUse } from "@redux/actions/account-actions/ListsActions";
import { useDispatch } from "react-redux";

interface IProps {
  list: any;
  role: any;
  iv: any;
  content: any;
}

export const InvitationPage: React.FC<IProps> = (props) => {
  const { list, role, iv, content } = props;
  const dispatch = useDispatch();

  console.log("InvPa", { list, role, iv, content });

  const router = useRouter();
  const user = useSelectorAccount((state) => state.user);

  async function onAcceptClick() {
    dispatch(
      inviteUse({
        data: {
          iv,
          content,
        },
        callback() {
          router.push(`/shopping-lists/${list.product_list_id}`);
        },
      })
    );
  }

  function editProfile() {
    router.push("/public-profile");
  }

  function onCancelClick() {
    router.push("/");
  }

  const avatar = user.avatar_image
    ? getStoreUrl(user.avatar_image)
    : "/static/frontend/images/pages/account/default-avatar.svg";

  return (
    <div>
      <MobileMenuBackBtn redirectUrl={`/`} label={"account"} />
      <div className="page-label">Collaboration invitation</div>
      <div className="page-invitation-subtitle">
        You have been invited to collaborate on <b>"{list.name}"</b> by{" "}
        {list.owner.name}
      </div>
      <div className="page-invitation-subtitle">
        You will appear to others in the List as{" "}
        <b>{viewUserListRight(role)}</b>
      </div>
      <div className="page-invitation-user-profile-container">
        <div className="page-invitation-user-profile">
          <img src={avatar} className="page-invitation-user-profile-avatar" />
          <div className="page-invitation-user-profile-name d-flex align-content-center">
            {user.public_name || user.name}
          </div>
        </div>
        <Button
          onClick={editProfile}
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
      />
    </div>
  );
};
