import React from "react";
import { ShareListManagePeopleSelect } from "@modules/account/components/lists/ShareListManagePeopleSelect";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import { viewUserListRight } from "@modules/account/utils/view-user-list-right";
import { useDialog } from "@modules/account/hooks/useDialog";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import getStoreUrl from "@utils/getStoreUrl";
import MobileMenu from "@modules/account/components/lists/share-list/MobileMenu";
import cn from "classnames";

interface IProps {
  userListInfo: Record<any, any>;
  role: string;
  onClick: (UserRightsType: UserRightsActionsEnum, userId: string) => void;
  userDelete?: any;
}

export const ManagePeopleItem: React.FC<IProps> = (props) => {
  const { userListInfo, role, onClick, userDelete } = props;
  const user = useSelectorAccount((e) => e.user);
  const isYourAccount = userListInfo.user_id === user.user_id;
  const mobileMenuDialog = useDialog();
  const defaultAvatar =
    "/static/frontend/images/pages/account/default-avatar.svg";
  const avatarImage = userListInfo.avatar_image
    ? getStoreUrl(userListInfo.avatar_image)
    : defaultAvatar;

  return (
    <div className="share-list-people-container justify-content-between">
      <div className="d-flex align-items-center share-list-people-left-side-container">
        <img
          src={avatarImage}
          className="page-invitation-user-profile-avatar"
          alt={""}
        />
        <div>
          <div>
            {userListInfo.name}
            {isYourAccount && "(You)"}
          </div>
          <div className="share-list-people-email">{userListInfo.email}</div>
        </div>
      </div>

      {isYourAccount || UserPrivateVariantsEnum.OWNER === role ? (
        <div className="share-list-account-role">{viewUserListRight(role)}</div>
      ) : (
        <>
          <div
            onClick={mobileMenuDialog.handleClickOpen}
            className="share-list-account-role-mobile d-md-none"
          >
            {viewUserListRight(role)}
          </div>
          <div className={cn("d-none", "d-md-block")}>
            <ShareListManagePeopleSelect
              items={[
                { value: UserRightsActionsEnum.EDIT, label: "Editor" },
                { value: UserRightsActionsEnum.VIEW, label: "Viewer" },
              ]}
              value={{
                value: role,
                label: viewUserListRight(role),
              }}
              onClick={(selectValue) =>
                onClick(selectValue.value, userListInfo.user_id)
              }
              name="right"
            />
          </div>
        </>
      )}

      <MobileMenu
        role={role}
        user={userListInfo}
        dialog={mobileMenuDialog}
        onClick={onClick}
        userDelete={userDelete}
      />
    </div>
  );
};

export default ManagePeopleItem;
