import React from "react";
import { useDispatch } from "react-redux";
import { ShareListManagePeopleItem } from "@modules/account/components/lists/ShareListManagePeopleItem";
import { changeUserRole } from "@redux/actions/account-actions/ListsActions";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";

interface IProps {
  list: Record<any, any>;
}

export const ShareListManagePeople: React.FC<IProps> = (props) => {
  const { list } = props;
  const dispatch = useDispatch();

  function handleSelectItemCLick(role: UserRightsActionsEnum, user_id: string) {
    dispatch(
      changeUserRole({
        data: {
          product_list_id: list.product_list_id,
          user_id,
          role,
        },
      })
    );
  }

  return (
    <React.Fragment>
      <div className="share-list-label">Manage people</div>
      <ShareListManagePeopleItem
        onClick={handleSelectItemCLick}
        userListInfo={list.owner}
        role={"owner"}
      />
      {list.users.map((item, i) => (
        <ShareListManagePeopleItem
          onClick={handleSelectItemCLick}
          userListInfo={item.user}
          role={item.role}
          key={`ShareListManagePeopleItem-${i}`}
        />
      ))}
    </React.Fragment>
  );
};
