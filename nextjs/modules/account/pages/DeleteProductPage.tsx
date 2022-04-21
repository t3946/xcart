import React from "react";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import { deleteItem } from "@redux/actions/account-actions/ListsActions";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";

interface IProps {
  list: any;
  listItem: any;
}

export const DeleteProductPage: React.FC<IProps> = (props) => {
  const { list, listItem } = props;
  const router = useRouter();
  const dispatch = useDispatch();

  function deleteHandler() {
    dispatch(
      deleteItem({
        data: { list_item_id: listItem.list_item_id },
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
        deleteType={listItem.product_type}
      />
    </div>
  );
};
