import React from "react";
import { Sceleton } from "@modules/shared/components/sceleton/Sceleton";

export const ListProductItemSkeleton: React.FC = () => {
  return (
    <div className="product-list-item-container">
      <Sceleton margin="0 20px 0 0 " height={112} maxWidth={112} />

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
