import React from "react";
import RatingStars from "@client/jsx/modules/shared/components/ratings/RatingStars";
import classnames from "classnames";
import OverallBars from "@client/jsx/modules/shared/components/ratings/OverallBars";

interface PropsInterface {
  minRating: number;
  maxRating: number;
  ratings: { rating: number; ratingsNumber: number }[];
  classes?: {
    overallRating?: any;
    rating?: {
      icon?: any;
      container?: any;
    };
  };
}

const OverallRating: React.FC<PropsInterface> = (
  props: PropsInterface
) => {
  const { ratings, maxRating, classes } = props;

  const totalRatingsNumber = ratings.reduce(
    (pv, cv) => pv + cv.ratingsNumber,
    0
  );

  const overallRating = countOverallRating();

  /**
   * @return number from 0 to max rating
   */
  function countOverallRating(): number {
    const totalRating = ratings.reduce(
      (pv, cv) => pv + cv.ratingsNumber * cv.rating,
      0
    );
    const maxTotalRating = maxRating * totalRatingsNumber;

    if (maxTotalRating === 0) {
      return 0;
    }

    return (totalRating / maxTotalRating) * maxRating;
  }

  return (
    <div className={classnames(classes?.overallRating)}>
      <div className="d-flex justify-content-between">
        <RatingStars rating={overallRating} classes={classes?.rating} />

        <div className="overall-rating-out-of-caption">
          {`${overallRating.toFixed(1)} out of ${maxRating}`}
        </div>
      </div>

      <div
        className={"overall-rating-global-ratings mt-2 d-none d-lg-block"}
      >{`${totalRatingsNumber.toLocaleString()} global ratings`}</div>

      <div className="overall-rating_bars">
        <OverallBars {...props} />
      </div>
    </div>
  );
};

export default OverallRating;
