import React from "react";
import { useHistory, useParams } from "react-router-dom";
import Store from "@redux/stores/Store";
import { ManageList } from "@modules/account/components/lists/ManageList";

interface ManageListPageURLParams {
  listHash: string;
}

export const ManageListPage: React.FC = () => {
  const params = useParams<ManageListPageURLParams>();

  const history = useHistory();

  const lists = Store.getState().lists.lists;

  if (!lists) {
    history.push(`/account/your-lists/${params.listHash}`);
    return;
  }

  const list = lists.find((e) => e.cache_url === params.listHash);

  const onCancelClick = () => {
    history.push(`/account/your-lists/${list.cache_url}`);
  };

  return (
    <div>
      <div className="page-label">Manage list</div>
      <ManageList info={list} onCancelClick={onCancelClick} />
    </div>
  );
};
