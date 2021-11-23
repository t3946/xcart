import React from "react";

interface IProps {
  ratings: { rating: string; totalRates: string }[];
}

const OverallBars: React.FC<IProps> = function (props: IProps): any {
  const { ratings } = props;
  const minRating = 1;
  const maxRating = 5;
  const bars = [];

  if (!ratings) {
    for (let i = minRating; i <= maxRating; i++) {
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
    (pv, cv) => pv + parseInt(cv.totalRates),
    0
  );

  function getRatingsNumber(rate): number {
    for (let j = 0; j < ratings.length; j++) {
      if (parseInt(ratings[j].rating) === rate) {
        return parseInt(ratings[j].totalRates);
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
    if (fraction > 0 && percents[rate] > 0) {
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
