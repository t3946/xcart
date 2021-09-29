import React from "react";
import StarFilled from "@client/jsx/modules/icon/components/account/rating/StarFilled";
import StarStroked from "@client/jsx/modules/icon/components/account/rating/StarStroked";
import classnames from "classnames";

interface PropsInterface {
  rate: number;
}

export const ProductStarsRating: React.FC<PropsInterface> = (
  props: PropsInterface
) => {
  const { rate } = props;
  const maxRate = 5;
  const stars = Array(maxRate).fill(null);
  const classes = {
    icon: [
      "rating-star",
      {
        "rating-star__red": rate > 0 && rate <= 2,
        "rating-star__yellow": rate === 0 || (rate > 2 && rate <= 3),
        "rating-star__green": rate > 3,
      },
    ],
  };

  return (
    <div className="rating-stars-container d-flex justify-content-between">
      {stars.map((e, index) => {
        if (index < rate) {
          return <StarFilled className={classnames(classes.icon)} />;
        }

        return <StarStroked className={classnames(classes.icon)} />;
      })}
    </div>
  );
};
