import React from "react";
import { useHistory, useParams } from "react-router-dom";
import Store from "@client/jsx/redux/stores/Store";
import { DeleteList } from "@client/modules/account/components/lists/DeleteList";
import { MobileMenuBackBtn } from "@client/modules/account/pages/MobileMenuBackBtn";

interface ManageListPageURLParams {
  listHash: string;
}

export const DeleteListPage: React.FC = () => {
  const params = useParams<ManageListPageURLParams>();

  const history = useHistory();

  const lists = Store.getState().lists.lists;

  if (!lists) {
    history.push("/account/your-lists/");
  }

  const list = lists.find((e) => e.cache_url === params.listHash);

  const onCancelClick = () => {
    history.push(`/account/your-lists/${list.cache_url}`);
  };

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/account/your-lists/${params.listHash}`}
        label={"back"}
      />
      <div className="page-label">Delete list</div>
      <DeleteList info={list} onCancelClick={onCancelClick} />
    </div>
  );
};
