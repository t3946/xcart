import React from "react";
import StarBorderIcon from "@material-ui/icons/StarBorder";
import StarIcon from "@material-ui/icons/Star";

export const ProductStarsRating = ({ rating }) => {
  const stars = Array(5).fill(null);

  return (
    <div className="product-stars-rating-container">
      {stars.map((e, index) => {
        if (index < rating) {
          return <StarIcon className="product-stars-rating-star" />;
        }
        return <StarBorderIcon className="product-stars-rating-star" />;
      })}
    </div>
  );
};
