import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { ShareListManagePeopleItem } from "@client/modules/account/components/lists/ShareListManagePeopleItem";
import { editUserRights } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { UserRightsActionsEnum } from "@client/modules/account/ts/consts/user-rights-actions.enum";

interface ShareListManagePeopleProps {
  id: string;
  closeDialog: () => void;
}

export const ShareListManagePeople: React.FC<ShareListManagePeopleProps> = ({
  id,
}) => {
  const list = useSelector((e: AccountStore) =>
    e.lists.lists.find((e) => e.cache_url === id)
  );

  const dispatch = useDispatch();

  const handleSelectItemCLick = (
    actionType: UserRightsActionsEnum,
    userId: string
  ) => {
    dispatch(editUserRights(list.product_list_id, userId, actionType));
  };

  return (
    <React.Fragment>
      <div className="share-list-label">Manage people</div>
      {list.users.map((item) => {
        return (
          <ShareListManagePeopleItem
            onClick={handleSelectItemCLick}
            userListInfo={item}
          />
        );
      })}
    </React.Fragment>
  );
};
