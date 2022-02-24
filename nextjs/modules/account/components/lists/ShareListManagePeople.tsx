import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { ShareListManagePeopleItem } from "@modules/account/components/lists/ShareListManagePeopleItem";
import { editUserRights } from "@redux/actions/account-actions/ListsActions";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";
import { List } from "@modules/account/ts/types/list.type";

interface ShareListManagePeopleProps {
  id: string;
  closeDialog: () => void;
}

export const ShareListManagePeople: React.FC<ShareListManagePeopleProps> = ({
  id,
}) => {
  const lists: List[] = useSelector((state) => state.lists.lists);
  const list: List = lists.find((list) => list.cacheUrl === id);
  const dispatch = useDispatch();

  const handleSelectItemCLick = (
    actionType: UserRightsActionsEnum,
    userId: string
  ) => {
    dispatch(editUserRights(list.productListId, userId, actionType));
  };
  return (
    <React.Fragment>
      <div className="share-list-label">Manage people</div>
      {list.users.map((item) => (
        <ShareListManagePeopleItem
          onClick={handleSelectItemCLick}
          userListInfo={item}
        />
      ))}
    </React.Fragment>
  );
};
