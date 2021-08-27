import React from "react";
import { ListItemMovableArea } from "@client/modules/account/components/lists/ListItemMovableArea";
import { Tooltip } from "@client/modules/account/components/shared/Tooltip";
import { ProductStarsRating } from "@client/modules/account/components/shared/ProductStarsRating";
import { TooltipRatingContent } from "@client/modules/account/components/lists/TooltipRatingContent";
import { ListProductItemBtns } from "@client/modules/account/components/lists/ListProductItemBtns";
import { Sceleton } from "@client/modules/shared/components/sceleton/Sceleton";
import { Button } from "@material-ui/core";

export const ListProductItemSkeleton = () => {
  return (
    <div className="product-list-item-container">
      <Sceleton height={112} maxWidth={112} />

      <div className="product-list-item-info">
        <Sceleton height={47} maxWidth={"100%"} />
        <div className="product-stars-rating-container-skeleton">
          <Sceleton height={24} maxWidth={140} />
        </div>
        <div className="product-list-item-price">
          <Sceleton height={24} maxWidth={40} />
        </div>
        <Sceleton height={24} maxWidth={200} />
      </div>
      <div className="skeleton-btns">
        <div className="list-product-item-btns-text">
          <Sceleton height={24} maxWidth={"100%"} />
        </div>
        <Sceleton height={34} maxWidth={"100%"} />
        <div className="list-product-item-btns-container">
          <Sceleton height={34} maxWidth={"46%"} />
          <Sceleton height={34} maxWidth={"50%"} />
        </div>
      </div>
    </div>
  );
};
