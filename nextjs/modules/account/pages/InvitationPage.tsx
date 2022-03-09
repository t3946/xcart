import React from "react";
import { Button } from "@material-ui/core";
import { useDispatch } from "react-redux";
import { viewUserListRight } from "@modules/account/utils/view-user-list-right";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import { ApiService } from "@modules/shared/services/api.service";
interface InvitationPage {
  inviteUser: string;
  type: UserPrivateVariantsEnum;
  listData: {
    productListId: number;
    name: string;
    cacheUrl: string;
  };
}
const api = new ApiService();
export const InvitationPage: React.FC<InvitationPage> = ({ ...other }) => {
  const router = useRouter();
  const editProfile = () => {
    router.push("/public-profile");
  };
  const onCancelClick = () => {
    router.push("/");
  };
  const user = useSelectorAccount((state) => state.user);
  const onAcceptClick = async () => {
    await api
      .post(
        `/api/account/lists/accept-invite`,
        JSON.stringify({
          listId: other.listData.productListId,
          role: other.type,
        })
      )
      .then((res) => res);
    await router.push("/shopping-lists");
  };
  const avatar = user.avatar_image
    ? `/${user.avatar_image}`
    : "/static/frontend/images/pages/account/default-avatar.svg";

  return (
    <div>
      <MobileMenuBackBtn redirectUrl={`/`} label={"account"} />
      <div className="page-label">Collaboration invitation</div>
      <div className="page-invitation-subtitle">
        You have been invited to collaborate on <b>"{other.listData.name}"</b>{" "}
        by {other.inviteUser}
      </div>
      <div className="page-invitation-subtitle">
        You will appear to others in the List as{" "}
        <b>{viewUserListRight(other.type)}</b>
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
