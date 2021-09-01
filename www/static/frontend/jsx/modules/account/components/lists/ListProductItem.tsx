import React from "react";
import { ListItemMovableArea } from "@client/modules/account/components/lists/ListItemMovableArea";
import { ProductStarsRating } from "@client/modules/account/components/shared/ProductStarsRating";
import { Tooltip } from "@client/modules/account/components/shared/Tooltip";
import { TooltipRatingContent } from "./TooltipRatingContent";
import { ListProductItemBtns } from "./ListProductItemBtns";
import { useDispatch } from "react-redux";
import { moveProduct } from "@client/jsx/redux/actions/account-actions/ListsActions";

export const ListProductItem = ({
  info,
  drag,
  reorderProductList,
  index,
  listId,
}) => {
  const dispatch = useDispatch();

  const onMoveClick = (value) => {
    setTimeout((e) => {
      dispatch(moveProduct(listId, value, info));
    }, 500);
  };

  return (
    <div className="product-list-item-container">
      <ListItemMovableArea
        onUpClick={() => reorderProductList(index, index - 1)}
        onDownClick={() => reorderProductList(index, index + 1)}
        drag={drag}
      />
      <img
        className="product-list-item-image"
        src="/static/frontend/images/icons/account/plus.svg"
      />
      <div className="product-list-item-info">
        <div className="product-list-item-name">{info.product.product}</div>
        <Tooltip
          target={
            <div className="tooltip-rating-stars-target">
              <ProductStarsRating rating={3} />
            </div>
          }
          content={
            <div className="rating-stars-tooltip">
              <TooltipRatingContent />
            </div>
          }
        />
        <div className="product-list-item-price">
          ${info.product.cost_to_us}
        </div>
        <div className="add-comment-text">Add comment, quantity & priority</div>
      </div>
      <ListProductItemBtns onMoveClick={onMoveClick} />
    </div>
  );
};
