import React from "react";
import StarFilled from "@client/jsx/modules/icon/components/account/rating/StarFilled";
import StarStroked from "@client/jsx/modules/icon/components/account/rating/StarStroked";
import classnames from "classnames";

interface PropsInterface {
  rating: number;
  classes?: {
    icon?: any;
    container?: any;
  };
}

export const ProductStarsRating: React.FC<PropsInterface> = (
  props: PropsInterface
) => {
  const rating = Math.round(props.rating);
  const maxRating = 5;
  const stars = Array(maxRating).fill(null);
  const classes = {
    container: [
      "rating-stars-container",
      "d-flex ",
      "justify-content-between",
      props.classes?.container,
    ],
    icon: [
      "rating-star",
      {
        "rating-star__red": rating > 0 && rating <= 2,
        "rating-star__yellow": rating === 0 || (rating > 2 && rating <= 3),
        "rating-star__green": rating > 3,
      },
      props.classes?.icon,
    ],
  };

  return (
    <div className={classnames(classes.container)}>
      {stars.map((e, index) => {
        if (index < rating) {
          return <StarFilled className={classnames(classes.icon)} />;
        }

        return <StarStroked className={classnames(classes.icon)} />;
      })}
    </div>
  );
};

export default ProductStarsRating;
