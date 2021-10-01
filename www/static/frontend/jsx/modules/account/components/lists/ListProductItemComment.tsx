import React from "react";
import { priorityProductSelectValuesConst } from "@client/modules/account/ts/consts/priority-product-select-values.const";
import { ListItem } from "@client/modules/account/ts/types/list.type";

interface ListProductItemCommentProps {
  info: ListItem;
  onEditCommentClick: () => void;
}

export const ListProductItemComment: React.FC<ListProductItemCommentProps> = ({
  info,
  onEditCommentClick,
}) => {
  const priority = priorityProductSelectValuesConst.find(
    (e) => e.value === info.priority
  ).viewValue;
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
      <div className="list-product-item-comment-delete">
        <svg
          width="26"
          height="26"
          viewBox="0 0 26 26"
          fill="none"
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
    </div>
  );
};
