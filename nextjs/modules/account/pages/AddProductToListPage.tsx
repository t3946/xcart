import React, { useEffect } from "react";
import { AddProductToList } from "@modules/account/components/lists/AddProductToList";
import { useParams } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { getLists } from "@redux/actions/account-actions/ListsActions";
import StoreInterface from "@modules/account/ts/types/store.type";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";

interface AddProductToListPageURLParams {
  listId: string;
  isAdded: string;
}

export const AddProductToListPage: React.FC = () => {
  const params = useParams<AddProductToListPageURLParams>();
  const lists = useSelector((e: StoreInterface) => e.lists.lists);
  const list = lists?.find((e) => e.product_list_id === params.listId);
  const dispatch = useDispatch();

  useEffect(() => {
    if (!list) {
      dispatch(getLists());
      console.log('getLists AddProductToListPage');
    }
  }, []);

  return (
    <div>
      {list && (
        <React.Fragment>
          <MobileMenuBackBtn
            redirectUrl={`/account/your-lists/${list?.cache_url}`}
            label={"back"}
          />
          <div className="page-label">Add to list</div>
          <AddProductToList
            onCancelClick={() => window.location.assign("/")}
            isAlreadyInList={params.isAdded === "true"}
            info={list}
            product={undefined}
          />
        </React.Fragment>
      )}
    </div>
  );
};
