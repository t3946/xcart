import React from "react";
import { useDispatch } from "react-redux";
import ManagePeopleItem from "@modules/account/components/lists/share-list/ManagePeopleItem";
import {
  roleUpdate,
  roleDelete,
} from "@redux/actions/account-actions/ListsActions";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";

interface IProps {
  list: Record<any, any>;
}

export const ShareListManagePeople: React.FC<IProps> = (props) => {
  const { list } = props;
  const dispatch = useDispatch();

  function userDelete(user_id: number) {
    dispatch(
      roleDelete({
        data: {
          product_list_id: list.product_list_id,
          user_id,
        },
      })
    );
  }

  function handleSelectItemClick(role: UserRightsActionsEnum, user_id: string) {
    dispatch(
      roleUpdate({
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

      <ManagePeopleItem
        onClick={handleSelectItemClick}
        userListInfo={list.owner}
        role={"owner"}
      />

      {list.users.map((item, i) => (
        <ManagePeopleItem
          onClick={handleSelectItemClick}
          userDelete={userDelete}
          userListInfo={item.user}
          role={item.role}
          key={`ShareListManagePeopleItem-${i}`}
        />
      ))}
    </React.Fragment>
  );
};
