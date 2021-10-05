import React from "react";
import RatingStars from "@client/jsx/modules/shared/components/ratings/RatingStars";
import classnames from "classnames";
import OverallBars from "@client/jsx/modules/shared/components/ratings/OverallBars";

interface PropsInterface {
  ratings: any;
  classes?: {
    overallRating?: any;
    rating?: {
      icon?: any;
      container?: any;
    };
  };
}

const OverallRating: React.FC<PropsInterface> = (props: PropsInterface) => {
  const { ratings } = props;
  const maxRating = 5;

  const classes = {
    overallRating: [props.classes.overallRating],
    rating: props.classes.rating,
    overallRatingStars: [
      "d-flex",
      "justify-content-between",
      {
        "skeleton-box": !ratings,
      },
    ],
    overallRatingGlobal: [
      "overall-rating-global-ratings",
      "mt-2",
      "d-none",
      "d-lg-block",
      {
        "skeleton-box": !ratings,
      },
    ],
  };

  let totalRatingsNumber = 0;
  let overallRating = 0;

  if (ratings) {
    totalRatingsNumber = ratings.rates.reduce(
      (pv, cv) => pv + parseInt(cv.totalRates),
      0
    );
    overallRating = parseFloat(ratings.total);
  }

  return (
    <div className={classnames(classes?.overallRating)}>
      <div className={classnames(classes?.overallRatingStars)}>
        <RatingStars rating={overallRating} classes={classes?.rating} />

        <div className="overall-rating-out-of-caption">
          {`${overallRating.toFixed(1)} out of ${maxRating}`}
        </div>
      </div>

      <div
        className={classnames(classes.overallRatingGlobal)}
      >{`${totalRatingsNumber.toLocaleString()} global ratings`}</div>

      <div className="overall-rating_bars">
        <OverallBars ratings={props.ratings?.rates} />
      </div>
    </div>
  );
};

export default OverallRating;
