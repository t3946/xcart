import React from "react";
import { ConfirmDelete } from "@client/modules/account/components/lists/ConfirmDelete";
import { deleteProduct } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { useParams } from "react-router-dom";
import { useDispatch } from "react-redux";
import { useHistory } from "react-router";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import { List } from "@client/modules/account/ts/types/list.type";

interface DeleteProductPageURLParams {
  listId: string;
  productId: string;
  type: string;
}

export const DeleteProductPage: React.FC = () => {
  const params = useParams<DeleteProductPageURLParams>();

  const dispatch = useDispatch();

  const history = useHistory();

  const deleteItem = () => {
    dispatch(deleteProduct(params.listId, params.productId, onCancelClick));
  };

  const list = accountStore
    .getState()
    .lists.lists.find((e: List) => e.product_list_id === params.listId);

  const onCancelClick = () => {
    history.push(`/account/your-lists/${list.cache_url}`);
  };
  return (
    <div>
      <div className="page-label">Delete {params.type}</div>
      <ConfirmDelete
        onCancelClick={onCancelClick}
        onDeleteClick={deleteItem}
        deleteType={params.type}
      />
    </div>
  );
};
