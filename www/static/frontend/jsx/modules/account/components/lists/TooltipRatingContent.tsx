import React from "react";
import { ProductStarsRating } from "../shared/ProductStarsRating";

export const TooltipRatingContent: React.FC = () => {
  return (
    <div className="tooltip-rating-content-container">
      <div className="tooltip-rating-content-top">
        <ProductStarsRating rating={2} />
        <div className="tooltip-rating-content-top-label">4.6 out of 5</div>
      </div>
      <p>2,401 global ratings</p>
      <div className="tooltip-rating-content-bar-container">
        {Array(5)
          .fill(null)
          .map((e, index) => {
            return (
              <div className="tooltip-rating-content-bar">
                <div>{index + 1} Star</div>
                <div className="rating-bar-container">
                  <div
                    className="rating-bar-rating"
                    style={{
                      width: `${index * 15}%`,
                    }}
                  />
                </div>
                <div className="rating-bar-score">{index * 15}%</div>
              </div>
            );
          })}
      </div>
      <div className="see-all-ratings-text">See all customer reviews</div>
    </div>
  );
};
