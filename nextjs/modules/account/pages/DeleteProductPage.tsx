import React from "react";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import { deleteItem } from "@redux/actions/account-actions/ListsActions";
import { useDispatch } from "react-redux";
import { List } from "@modules/account/ts/types/list.type";
import { useRouter } from "next/router";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

export const DeleteProductPage: React.FC = () => {
  const router = useRouter();
  const { lists } = useSelectorAccount((state) => state.lists);
  const { productListId, itemId, entityType } = router.query;
  const dispatch = useDispatch();
  const list: List = lists.find(
    (e: List) => e.productListId === Number(productListId)
  );

  function deleteHandler() {
    dispatch(
      deleteItem({
        data: { list_item_id: parseInt(itemId) },
      })
    );

    goToListPage();
  }

  function goToListPage() {
    router.push(`/shopping-lists/${list.cacheUrl}`);
  }

  return (
    <div>
      <div className="page-label">Delete</div>
      <ConfirmDelete
        onCancelClick={goToListPage}
        onDeleteClick={deleteHandler}
        deleteType={entityType}
      />
    </div>
  );
};
