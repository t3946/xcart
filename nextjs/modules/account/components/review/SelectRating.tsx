import React from "react";
import StarFilled from "@modules/icon/components/account/rating/StarFilled";
import StarStroked from "@modules/icon/components/account/rating/StarStroked";
import { Form as RBForm } from "react-bootstrap";
import classnames from "classnames";

interface IProps {
  name: string;
  handleChange: (e: any) => any;
  value: number;
  classes?: {
    container?: any;
    star?: any;
  };
  reset: any;
}

const SelectRating: React.FC<IProps> = function (
  props: IProps
) {
  const { handleChange, value, name, reset } = props;
  const maxRating = 5;
  const stars = [];
  const [hoverIndex, setHoverIndex] = React.useState(0);

  const classes = {
    container: [props.classes?.container],
    star: [props.classes?.star],
  };

  function starTemplate(i: number) {
    const starClasses = [
      {
        "form-review-star_hover": i <= hoverIndex,
      },
      classes.star,
    ];

    if (i <= value) {
      return <StarFilled className={starClasses} />;
    }

    return <StarStroked className={starClasses} />;
  }

  for (let i = 1; i <= maxRating; i++) {
    stars.push(
      <RBForm.Label
        className={"m-0 star-container"}
        onMouseEnter={() => setHoverIndex(i)}
        onMouseLeave={() => setHoverIndex(0)}
        key={`label-${i}`}
      >
        <RBForm.Check
          type="radio"
          name={name}
          value={i}
          onChange={handleChange}
          className={"d-none"}
          checked={i === value}
          onClick={() => {
            if (i === value) {
              reset();
            }
          }}
          key={`check-${i}`}
        />

        {starTemplate(i)}
      </RBForm.Label>
    );
  }

  return <div className={classnames(classes.container)}>{stars}</div>;
};

export default SelectRating;
