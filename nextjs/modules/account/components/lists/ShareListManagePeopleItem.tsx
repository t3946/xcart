import React from "react";
import { ShareListManagePeopleSelect } from "@modules/account/components/lists/ShareListManagePeopleSelect";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import { viewUserListRight } from "@modules/account/utils/view-user-list-right";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { useDialog } from "@modules/account/hooks/useDialog";
import { MobileMenuForListItem } from "@modules/account/ts/types/MobileMenuForListItem";
import { MobileMenuForList } from "@modules/account/components/lists/MobileMenuForList";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import getStoreUrl from "@utils/getStoreUrl";

interface IProps {
  userListInfo: Record<any, any>;
  role: string;
  onClick: (UserRightsType: UserRightsActionsEnum, userId: string) => void;
}

export const ShareListManagePeopleItem: React.FC<IProps> = (props) => {
  const { userListInfo, role, onClick } = props;
  const user = useSelectorAccount((e) => e.user);
  const isYourAccount = userListInfo.user_id === user.user_id;
  const breakpoint = useBreakpoint();
  const mobileMenuDialog = useDialog();
  const defaultAvatar =
    "/static/frontend/images/pages/account/default-avatar.svg";
  const avatarImage = userListInfo.avatar_image
    ? getStoreUrl(userListInfo.avatar_image)
    : defaultAvatar;

  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      component: (
        <div className="d-flex align-items-center share-list-people-left-side-container">
          <img
            src={avatarImage}
            className="page-invitation-user-profile-avatar 1"
            alt={"avatar image"}
          />
          <div>
            <div>{userListInfo.name}</div>
            <div className="share-list-people-email">{userListInfo.email}</div>
          </div>
        </div>
      ),
    },
    {
      label: (
        <div
          className={`share-list-mobile-menu-item ${
            role === UserPrivateVariantsEnum.EDIT &&
            "share-list-mobile-menu-item-selected"
          }`}
        >
          Edit
        </div>
      ),
      onClick: () => {
        onClick(UserRightsActionsEnum.EDIT, userListInfo.user_id);
        mobileMenuDialog.handleClose();
      },
    },
    {
      label: (
        <div
          className={`share-list-mobile-menu-item ${
            role === UserPrivateVariantsEnum.VIEW &&
            "share-list-mobile-menu-item-selected"
          }`}
        >
          View
        </div>
      ),
      onClick: () => {
        onClick(UserRightsActionsEnum.VIEW, userListInfo.user_id);
        mobileMenuDialog.handleClose();
      },
    },
    {
      label: (
        <div className="share-list-mobile-menu-item share-list-mobile-menu-item-remove">
          Remove
        </div>
      ),
      onClick: () => {
        onClick(UserRightsActionsEnum.DELETE, userListInfo.user_id);
        mobileMenuDialog.handleClose();
      },
    },
  ];
  return (
    <div className="share-list-people-container justify-content-between">
      <div className="d-flex align-items-center share-list-people-left-side-container">
        <img
          src={avatarImage}
          className="page-invitation-user-profile-avatar"
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
        breakpoint({
          xs: (
            <div
              onClick={mobileMenuDialog.handleClickOpen}
              className="share-list-account-role-mobile"
            >
              {viewUserListRight(role)}
            </div>
          ),
          md: (
            <ShareListManagePeopleSelect
              items={[
                { value: UserRightsActionsEnum.EDIT, label: "Editor" },
                { value: UserRightsActionsEnum.VIEW, label: "Viewer" },
              ]}
              value={{
                value: role,
                label: viewUserListRight(role),
              }}
              id={userListInfo.user_id}
              onClick={(selectValue) =>
                onClick(selectValue.value, userListInfo.user_id)
              }
              name="right"
            />
          ),
        })
      )}
      <MobileMenuForList
        items={mobileDialogItems}
        dialogOpen={mobileMenuDialog.open}
        dialogOnClose={mobileMenuDialog.handleClose}
      />
    </div>
  );
};
