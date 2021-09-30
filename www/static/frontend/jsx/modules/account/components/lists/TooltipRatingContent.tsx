import React from "react";
import ProductStarsRating from "@client/modules/account/components/shared/ProductStarsRating";
import classnames from "classnames";

interface PropsInterface {
  minRating: number;
  maxRating: number;
  ratings: { rating: number; ratings_number: number }[];
  classes?: {
    overallRating?: any;
    rating?: {
      icon?: any;
      container?: any;
    };
  };
}

const TooltipRatingContent: React.FC<PropsInterface> = (
  props: PropsInterface
) => {
  /**
   * @return number from 0 to max rating
   */
  function countOverallRating(): number {
    console.log("countOverallRating", totalRatingsNumber);
    const totalRating = ratings.reduce((pv, cv) => pv + cv.rating, 0);
    const maxTotalRating = maxRating * totalRatingsNumber;

    if (maxTotalRating === 0) {
      return 0;
    }

    return (totalRating / maxTotalRating) * maxRating;
  }

  const { ratings, minRating, maxRating, classes } = props;
  const totalRatingsNumber = ratings.reduce(
    (pv, cv) => pv + cv.ratings_number,
    0
  );
  const overallRating = countOverallRating();

  function ratingBarsTemplate() {
    const bars = [];

    function getRatingsNumber(rate) {
      return 0;
    }

    for (let rate = maxRating; rate >= minRating; rate--) {
      const ratingsNumber = getRatingsNumber(rate);

      let percent = 0;

      if (totalRatingsNumber > 0) {
        percent = (ratingsNumber / totalRatingsNumber) * 100;
      }

      bars.push(
        <div className="d-flex justify-content-between align-items-center overall-rating_bar-group">
          <div className={"overall-rating-bar-caption"}>{rate} Star</div>

          <div className="overall-rating-bar overall-rating_bar">
            <div
              className="overall-rating-slider"
              style={{
                width: `${percent}%`,
              }}
            />
          </div>

          <div
            className={"overall-rating-percent text-end "}
          >{`${percent}%`}</div>
        </div>
      );
    }

    return bars;
  }

  return (
    <>
      <div className={classnames("overall-rating", classes?.overallRating)}>
        <div className="d-flex justify-content-between">
          <ProductStarsRating rating={2} classes={classes?.rating} />

          <div className="overall-rating-out-of-caption">
            {`${overallRating} out of ${maxRating}`}
          </div>
        </div>

        <div
          className={"overall-rating-global-ratings mt-2 d-none d-lg-block"}
        >{`${totalRatingsNumber.toLocaleString()} global ratings`}</div>

        <div className="overall-rating_bars">{ratingBarsTemplate()}</div>
      </div>

      <div className="see-all-ratings-text">See all customer reviews</div>
    </>
  );
};

export default TooltipRatingContent;
