import React from "react";
import Store from "@client/jsx/redux/stores/Store";
import { ShareListManagePeopleSelect } from "@client/modules/account/components/lists/ShareListManagePeopleSelect";
import { UserPrivateVariantsEnum } from "@client/modules/account/ts/consts/user-private-variants.enum";
import { viewUserListRight } from "@client/modules/account/utils/view-user-list-right";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import { useDialog } from "@client/modules/account/hooks/useDialog";
import { MobileMenuForListItem } from "@client/modules/account/ts/types/MobileMenuForListItem";
import { MobileMenuForList } from "@client/modules/account/components/lists/MobileMenuForList";
import { UserRightsActionsEnum } from "@client/modules/account/ts/consts/user-rights-actions.enum";
import { ListProductUser } from "@client/modules/account/ts/types/list.type";

interface ShareListManagePeopleItem {
  userListInfo: ListProductUser;
  onClick: (UserRightsType: UserRightsActionsEnum, userId: string) => void;
}

export const ShareListManagePeopleItem: React.FC<ShareListManagePeopleItem> = ({
  userListInfo,
  onClick,
}) => {
  const isYourAccount = userListInfo.user.user_id === Store.getState().user.id;

  const breakpoint = useBreakpoint();

  const mobileMenuDialog = useDialog();

  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      component: (
        <div className="d-flex align-items-center share-list-people-left-side-container">
          <img
            src="/static/frontend/images/pages/account/default-avatar.svg"
            className="page-invitation-user-profile-avatar"
          />
          <div>
            <div>{userListInfo.user.name}</div>
            <div className="share-list-people-email">
              {userListInfo.user.email}
            </div>
          </div>
        </div>
      ),
    },
    {
      label: (
        <div
          className={`share-list-mobile-menu-item ${
            userListInfo.role === UserPrivateVariantsEnum.EDIT &&
            "share-list-mobile-menu-item-selected"
          }`}
        >
          Edit
        </div>
      ),
      onClick: () => {
        onClick(UserRightsActionsEnum.EDIT, userListInfo.user.user_id);
        mobileMenuDialog.handleClose();
      },
    },
    {
      label: (
        <div
          className={`share-list-mobile-menu-item ${
            userListInfo.role === UserPrivateVariantsEnum.VIEW &&
            "share-list-mobile-menu-item-selected"
          }`}
        >
          View
        </div>
      ),
      onClick: () => {
        onClick(UserRightsActionsEnum.VIEW, userListInfo.user.user_id);
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
        onClick(UserRightsActionsEnum.DELETE, userListInfo.user.user_id);
        mobileMenuDialog.handleClose();
      },
    },
  ];
  return (
    <div className="share-list-people-container justify-content-between">
      <div className="d-flex align-items-center share-list-people-left-side-container">
        <img
          src="/static/frontend/images/pages/account/default-avatar.svg"
          className="page-invitation-user-profile-avatar"
        />
        <div>
          <div>
            {userListInfo.user.name}
            {isYourAccount && "(You)"}
          </div>
          <div className="share-list-people-email">
            {userListInfo.user.email}
          </div>
        </div>
      </div>
      {isYourAccount || UserPrivateVariantsEnum.OWNER === userListInfo.role ? (
        <div className="share-list-account-role">
          {viewUserListRight(userListInfo.role)}
        </div>
      ) : (
        breakpoint({
          xs: (
            <div
              onClick={mobileMenuDialog.handleClickOpen}
              className="share-list-account-role-mobile"
            >
              {viewUserListRight(userListInfo.role)}
            </div>
          ),
          md: (
            <ShareListManagePeopleSelect
              items={[
                { value: UserRightsActionsEnum.EDIT, viewValue: "Edit" },
                { value: UserRightsActionsEnum.VIEW, viewValue: "View" },
              ]}
              value={{
                value: userListInfo.role,
                viewValue: viewUserListRight(userListInfo.role),
              }}
              id={userListInfo.user.user_id}
              name={"14324"}
              onClick={(selectValue) =>
                onClick(selectValue.value, userListInfo.user.user_id)
              }
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
