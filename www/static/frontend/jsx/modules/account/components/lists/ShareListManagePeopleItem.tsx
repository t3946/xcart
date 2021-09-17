import React from "react";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import { ShareListManagePeopleSelect } from "@client/modules/account/components/lists/ShareListManagePeopleSelect";
import { UserPrivateVariantsEnum } from "@client/modules/account/ts/consts/user-private-variants.enum";
import { viewUserListRight } from "@client/modules/account/utils/view-user-list-right";

export const ShareListManagePeopleItem = ({ userListInfo, onClick }) => {
  const isYourAccount =
    userListInfo.user.user_id === accountStore.getState().user.id;
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
        <ShareListManagePeopleSelect
          items={[
            { value: UserPrivateVariantsEnum.EDIT, viewValue: "Edit" },
            { value: UserPrivateVariantsEnum.VIEW, viewValue: "View" },
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
      )}
    </div>
  );
};
