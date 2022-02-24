import React from "react";
import RatingStars from "@modules/shared/components/ratings/RatingStars";
import classnames from "classnames";
import OverallBars from "@modules/shared/components/ratings/OverallBars";

interface IProps {
  ratings: any;
  classes?: {
    overallRating?: any;
    rating?: {
      icon?: any;
      container?: any;
    };
  };
}

const OverallRating: React.FC<IProps> = (props: IProps) => {
  const { ratings } = props;
  const maxRating = 5;

  const classes = {
    overallRating: [props?.classes?.overallRating],
    rating: props?.classes?.rating,
    overallRatingStars: [
      "d-flex",
      "justify-content-between",
      {
        "skeleton-box": ratings === undefined,
      },
    ],
    overallRatingGlobal: [
      "overall-rating-global-ratings",
      "mt-2",
      "d-none",
      "d-lg-block",
      {
        "skeleton-box": ratings === undefined,
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

  let rates;

  switch (props.ratings) {
    case undefined:
      rates = null;
      break;
    case null:
      rates = [];
      break;
    default:
      rates = props.ratings.rates;
  }

  return (
    <div className={classnames(classes?.overallRating)}>
      <div className={classnames(classes?.overallRatingStars)}>
        <RatingStars rating={overallRating} classes={classes?.rating} />

        {totalRatingsNumber > 0 && (
          <div className="overall-rating-out-of-caption">
            {`${overallRating.toFixed(1)} out of ${maxRating}`}
          </div>
        )}
      </div>

      <div
        className={classnames(classes.overallRatingGlobal)}
      >{`${totalRatingsNumber.toLocaleString()} global ratings`}</div>

      <div className="overall-rating_bars">
        <OverallBars ratings={rates} />
      </div>
    </div>
  );
};

export default OverallRating;
