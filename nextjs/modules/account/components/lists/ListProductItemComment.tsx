import React from "react";
import { priorityProductSelectValuesConst } from "@modules/account/ts/consts/priority-product-select-values.const";
import { List, ListItem } from "@modules/account/ts/types/list.type";
import { useDialog } from "@modules/account/hooks/useDialog";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import { useDispatch } from "react-redux";
import { editCommentInProduct } from "@redux/actions/account-actions/ListsActions";
import { PriorityProductEnum } from "@modules/account/ts/consts/priority-product.enum";

interface ListProductItemCommentProps {
  info: ListItem;
  listInfo: List;
  onEditCommentClick: () => void;
}

export const ListProductItemComment: React.FC<ListProductItemCommentProps> = ({
  info,
  listInfo,
  onEditCommentClick,
}) => {
  const priority = priorityProductSelectValuesConst.find(
    (e) => e.value === info.priority
  ).viewValue;

  const deleteCommentDialog = useDialog();

  const dispatch = useDispatch();

  const deleteComment = () => {
    dispatch(
      editCommentInProduct(
        listInfo.product_list_id,
        info.product_id,
        {
          comment: null,
          priority: null,
          has: null,
          needs: null,
        },
        deleteCommentDialog.handleClose
      )
    );
  };

  return (
    <div className={"list-product-item-comment-container"}>
      <div className="list-product-item-comment-container-text">
        {info.comment}
      </div>
      <div className="list-product-items-comment-params">
        <div className={"d-flex"}>
          <div className="list-product-item-comment-param">Priority:</div>
          <div className="list-product-item-comment-param-value priority">
            {priority}
          </div>
        </div>
        <div className={"d-flex"}>
          <div className="list-product-item-comment-param">Need:</div>
          <div className="list-product-item-comment-param-value needs">
            {info.needs}
          </div>
        </div>
        <div className={"d-flex"}>
          <div className="list-product-item-comment-param">Has:</div>
          <div className="list-product-item-comment-param-value">
            {info.has}
          </div>
        </div>
      </div>
      <div onClick={onEditCommentClick} className="add-comment-text">
        Edit comment, quantity & priority
      </div>
      <div
        onClick={deleteCommentDialog.handleClickOpen}
        className="list-product-item-comment-delete"
      >
        <svg
          width="26"
          height="26"
          viewBox="0 0 26 26"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M19.4998 6.5L6.49975 19.5"
            stroke="#4A4949"
            stroke-width="1.5"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            d="M6.50024 6.5L19.5002 19.5"
            stroke="#4A4949"
            stroke-width="1.5"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </div>
      <BootstrapDialogHOC
        show={deleteCommentDialog.open}
        title={"Confirm Delete"}
        onClose={deleteCommentDialog.handleClose}
      >
        <ConfirmDelete
          onCancelClick={deleteCommentDialog.handleClose}
          onDeleteClick={deleteComment}
          deleteType={"comment"}
        />
      </BootstrapDialogHOC>
    </div>
  );
};
