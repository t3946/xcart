import React from "react";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import { deleteProduct } from "@redux/actions/account-actions/ListsActions";
import { useParams } from "react-router-dom";
import { useDispatch } from "react-redux";
import { useHistory } from "react-router";

interface DeleteProductPageURLParams {
  listId: string;
  productId: string;
}

export const DeleteIdeaPage: React.FC = () => {
  const params = useParams<DeleteProductPageURLParams>();

  const dispatch = useDispatch();

  const history = useHistory();

  const deleteItem = () => {
    dispatch(deleteProduct(params.listId, params.productId, onCancelClick));
  };

  const onCancelClick = () => {
    history.push(`/account/your-lists/${params.listId}`);
  };
  return (
    <ConfirmDelete
      onCancelClick={onCancelClick}
      onDeleteClick={deleteItem}
      deleteType={"idea"}
    />
  );
};
