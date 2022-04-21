import React from "react";
import { priorityProductSelectValuesConst } from "@modules/account/ts/consts/priority-product-select-values.const";
import { List, ListItem } from "@modules/account/ts/types/list.type";
import { useDialog } from "@modules/account/hooks/useDialog";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import { useDispatch } from "react-redux";
import { editCommentProduct } from "@redux/actions/account-actions/ListsActions";
import cn from "classnames";
import Styles from "@modules/account/components/lists/item/Comment.module.scss";

interface IProps {
  listItem: ListItem;
  list: List;
  onEditCommentClick: () => void;
}

export const Comment: React.FC<IProps> = (props) => {
  const { listItem, list, onEditCommentClick } = props;
  const priority = priorityProductSelectValuesConst.find(
    (e) => e.value === listItem.priority
  ).label;
  const deleteCommentDialog = useDialog();
  const dispatch = useDispatch();
  const deleteComment = () => {
    dispatch(
      editCommentProduct({
        data: {
          comment: null,
          priority: null,
          has: null,
          needs: null,
          list_item_id: listItem.list_item_id,
        },
        callback: deleteCommentDialog.handleClose,
      })
    );
  };

  return (
    <div
      className={cn(
        Styles.listProductItemCommentContainer,
        "list-product-item-comment-container",
        "mb-10",
        "mb-md-0"
      )}
    >
      <div className="list-product-item-comment-container-text">
        {listItem.comment}
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
            {listItem.needs}
          </div>
        </div>
        <div className={"d-flex"}>
          <div className="list-product-item-comment-param">Have:</div>
          <div className="list-product-item-comment-param-value">
            {listItem.has}
          </div>
        </div>
      </div>
      <div
        onClick={onEditCommentClick}
        className={cn(Styles.editComment, "add-comment-text")}
      >
        Edit comment, quantity & priority
      </div>
      <div
        onClick={deleteCommentDialog.handleClickOpen}
        className={cn(Styles.comment__removeButton, "cursor-pointer", "d-flex")}
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
            strokeWidth="1.5"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
          <path
            d="M6.50024 6.5L19.5002 19.5"
            stroke="#4A4949"
            strokeWidth="1.5"
            strokeLinecap="round"
            strokeLinejoin="round"
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

export default Comment;
