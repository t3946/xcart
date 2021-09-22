import React from "react";
import { useHistory, useParams } from "react-router-dom";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import { ManageList } from "@client/modules/account/components/lists/ManageList";
import { DeleteList } from "@client/modules/account/components/lists/DeleteList";

interface ManageListPageURLParams {
  listHash: string;
}

export const DeleteListPage: React.FC = () => {
  const params = useParams<ManageListPageURLParams>();

  const history = useHistory();

  const list = accountStore
    .getState()
    .lists.lists.find((e) => e.cache_url === params.listHash);

  const onCancelClick = () => {
    history.push(`/account/your-lists/${list.cache_url}`);
  };

  console.log(list);

  return (
    <div>
      <div className="page-label">Manage list</div>
      <DeleteList info={list} onCancelClick={onCancelClick} />
    </div>
  );
};
