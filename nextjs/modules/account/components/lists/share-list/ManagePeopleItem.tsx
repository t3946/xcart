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
  user: Record<any, any>;
  role: string;
  onClick: (UserRightsType: UserRightsActionsEnum, userId: string) => void;
  userDelete?: any;
  list: any;
}

export const ManagePeopleItem: React.FC<IProps> = (props) => {
  const { list, user, role, onClick, userDelete } = props;
  const userCurrent = useSelectorAccount((e) => e.user);
  const isYourAccount = user.user_id === userCurrent.user_id;
  const mobileMenuDialog = useDialog();
  const defaultAvatar =
    "/static/frontend/images/pages/account/default-avatar.svg";
  const avatarImage = user.avatar_image
    ? getStoreUrl(user.avatar_image)
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
            {user.name}
            {isYourAccount && "(You)"}
          </div>
          <div className="share-list-people-email">{user.email}</div>
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
              user={user}
              list={list}
              items={[
                { value: UserRightsActionsEnum.EDIT, label: "Editor" },
                { value: UserRightsActionsEnum.VIEW, label: "Viewer" },
              ]}
              value={{
                value: role,
                label: viewUserListRight(role),
              }}
              onClick={(selectValue) =>
                onClick(selectValue.value, user.user_id)
              }
              name="right"
            />
          </div>
        </>
      )}

      <MobileMenu
        role={role}
        user={user}
        dialog={mobileMenuDialog}
        onClick={onClick}
        userDelete={userDelete}
      />
    </div>
  );
};

export default ManagePeopleItem;
