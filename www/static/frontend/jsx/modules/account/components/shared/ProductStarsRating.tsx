import React from "react";
import StarFilled from "@client/jsx/modules/icon/components/account/rating/StarFilled";
import StarStroked from "@client/jsx/modules/icon/components/account/rating/StarStroked";

interface PropsInterface {
  rating: number;
}

export const ProductStarsRating: React.FC<PropsInterface> = (
  props: PropsInterface
) => {
  const { rating } = props;
  const maxRate = 5;
  const stars = Array(maxRate).fill(null);

  return (
    <div className="product-stars-rating-container">
      {stars.map((e, index) => {
        if (index < rating) {
          return <StarFilled className="product-stars-rating-star" />;
        }

        return <StarStroked className="product-stars-rating-star" />;
      })}
    </div>
  );
};
