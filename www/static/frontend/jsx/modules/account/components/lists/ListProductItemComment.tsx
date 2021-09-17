import React from "react";
import { priorityProductSelectValuesConst } from "@client/modules/account/ts/consts/priority-product-select-values.const";

export const ListProductItemComment = ({ info, onEditCommentClick }) => {
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
    </div>
  );
};
