import React from "react";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import { deleteProduct } from "@redux/actions/account-actions/ListsActions";
import { useDispatch } from "react-redux";
import Store from "@redux/stores/Store";
import { List } from "@modules/account/ts/types/list.type";
import { useRouter } from "next/router";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

interface DeleteProductPageURLParams {
  listId: string;
  productId: string;
  type: string;
}

export const DeleteProductPage: React.FC = () => {
  const router = useRouter();
  const { lists } = useSelectorAccount((state) => state.lists);
  const { productListId, list_product_id, entityType } = router.query;
  const dispatch = useDispatch();

  const deleteItem = () => {
    dispatch(deleteProduct(Number(list_product_id), onCancelClick));
  };

  const list: List = lists.find(
    (e: List) => e.productListId === Number(productListId)
  );

  const onCancelClick = () => {
    router.push(`/shopping-lists/${list.cacheUrl}`);
  };
  return (
    <div>
      <div className="page-label">Delete</div>
      <ConfirmDelete
        onCancelClick={onCancelClick}
        onDeleteClick={deleteItem}
        deleteType={entityType}
      />
    </div>
  );
};
