import React, { useEffect } from "react";
import { AddProductToList } from "@client/modules/account/components/lists/AddProductToList";
import { useParams } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { getLists } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { MobileMenuBackBtn } from "@client/modules/account/pages/MobileMenuBackBtn";

interface AddProductToListPageURLParams {
  listId: string;
  isAdded: string;
}

export const AddProductToListPage = () => {
  const params = useParams<AddProductToListPageURLParams>();

  const lists = useSelector((e: AccountStore) => e.lists.lists);

  const list = lists?.find((e) => e.product_list_id === params.listId);

  const dispatch = useDispatch();

  useEffect(() => {
    if (!list) {
      dispatch(getLists());
    }
  }, []);

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/account/your-lists/${list.cache_url}`}
        label={"back"}
      />
      <div className="page-label">Add to list</div>
      {list && (
        <AddProductToList
          onCancelClick={() => window.location.assign("/")}
          isAlreadyInList={params.isAdded === "true"}
          info={list}
        />
      )}
    </div>
  );
};
