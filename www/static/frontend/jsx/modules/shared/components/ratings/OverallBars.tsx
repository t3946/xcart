import React from "react";

interface PropsInterface {
  minRating: number;
  maxRating: number;
  ratings: { rating: number; ratingsNumber: number }[];
}

const OverallBars: React.FC<PropsInterface> = function (
  props: PropsInterface
): any {
  const { ratings, maxRating, minRating } = props;
  const bars = [];

  if (!ratings) {
    for (let i = 0; i < 5; i++) {
      bars.push(
        <div className="skeleton-box d-flex justify-content-between align-items-center overall-rating_bar-group">
          <div className={"overall-rating-bar-caption"}>0 Star</div>

          <div className="overall-rating-bar overall-rating_bar">
            <div
              className="overall-rating-slider"
              style={{
                width: 0,
              }}
            />
          </div>

          <div className={"overall-rating-percent text-end "}>0%</div>
        </div>
      );
    }
    return bars;
  }

  const totalRatingsNumber = ratings.reduce(
    (pv, cv) => pv + cv.ratingsNumber,
    0
  );

  function getRatingsNumber(rate) {
    for (let j = 0; j < ratings.length; j++) {
      if (ratings[j].rating === rate) {
        return ratings[j].ratingsNumber;
      }
    }

    return 0;
  }

  const percents = [];
  //concern about total percent count always equal 100
  let fraction = 0;

  for (let rate = maxRating; rate >= minRating; rate--) {
    const ratingsNumber = getRatingsNumber(rate);

    let percent = 0;

    if (totalRatingsNumber > 0) {
      percent = (ratingsNumber / totalRatingsNumber) * 100;
      fraction += percent % 1;
    }

    percents[rate] = Math.floor(percent);
  }

  fraction = Math.round(fraction);

  //distribute left percents from fraction
  for (let rate = maxRating; rate >= minRating; rate--) {
    if (fraction > 0) {
      percents[rate] += 1;
      fraction -= 1;
    }
  }

  for (let rate = maxRating; rate >= minRating; rate--) {
    const percent = percents[rate];

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

  return <>{bars}</>;
};

export default OverallBars;
