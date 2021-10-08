import React from "react";
import RatingStars from "@client/jsx/modules/shared/components/ratings/RatingStars";
import ArrowIcon from "@client/modules/icon/components/account/chevron-down/AccountSidebarTablet";
import classnames from "classnames";

interface PropsInterface {
  product_review_id: string;
  user_id: string;
  product_id: string;
  header: string;
  body: string;
  location: string;
  created: string;
  overall_rating: string;
  user_public_name: string;
  user_avatar: string;
}

const Review: React.FC<PropsInterface> = function (props: PropsInterface) {
  const refBodyContainer = React.useRef<HTMLDivElement>();
  const [bodyHeight, setBodyHeight] = React.useState(null);
  const [isFullBodyExpanded, setIsFullBodyExpanded] = React.useState(false);
  const bodyHeightWhenTurned = 300;
  const rating = parseFloat(props.overall_rating);
  const classes = {
    readMore: [
      "spoiler-icon_arrow",
      "me-1",
      {
        "spoiler-icon_flip": isFullBodyExpanded,
      },
    ],
    header: [
      "review__header",
      "review-header",
      {
        "rating-star__red": rating > 0 && rating <= 2,
        "rating-star__yellow": rating === 0 || (rating > 2 && rating <= 3),
        "rating-star__green": rating > 3,
      },
    ],
  };

  function formatDate(date: string) {
    return new Date(date).toLocaleDateString("en-US", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });
  }

  function getLocation() {
    return props.location ? "in the " + props.location : "from the Earth";
  }

  function readMoreTemplate() {
    if (bodyHeight > bodyHeightWhenTurned) {
      return (
        <div
          className="d-flex align-items-center common-link common-link_spoiler"
          onClick={() => setIsFullBodyExpanded(!isFullBodyExpanded)}
        >
          <ArrowIcon className={classnames(classes.readMore)} />{" "}
          <span>Read more</span>
        </div>
      );
    }
  }

  React.useEffect(() => {
    if (bodyHeight === null) {
      setBodyHeight(refBodyContainer.current.clientHeight);
    }
  });

  function bodyTemplate() {
    const classes = {
      reviewBody: [
        "review-body-wrapper",
        {
          "review-body-wrapper_expanded": isFullBodyExpanded,
        },
      ],
    };

    return (
      <div className={"review-body"}>
        <div className={classnames(classes.reviewBody)}>
          <div className={"review-body-container"} ref={refBodyContainer}>
            {props.body}
          </div>
        </div>

        {readMoreTemplate()}
      </div>
    );
  }

  return (
    <div className={"reviews__review review"}>
      <div>
        <img
          src={"/" + props.user_avatar}
          alt={props.user_public_name}
          width={32}
          height={32}
          className={"review-avatar-image review__avatar"}
        />
        {props.user_public_name}
      </div>

      <div className={"d-md-flex align-items-center"}>
        <RatingStars rating={parseFloat(props.overall_rating)} />
        <h3 className={classnames(classes.header)}>{props.header}</h3>
      </div>

      <div className={"review-location review__location"}>
        <div className={"review-gray-text"}>
          Reviewed {getLocation(props.location)} on {formatDate(props.created)}
        </div>

        <span className={"review-verified-purchase"}>Verified Purchase</span>
      </div>

      {bodyTemplate()}

      <div>
        <p className={"review-gray-text mt-2 mt-md-3"}>
          23 people found this helpful
        </p>

        <div
          className={
            "d-flex flex-column flex-md-row align-items-center mx--10 mx-md-0"
          }
        >
          <button
            className={"form-button form-button__outline w-100 w-md-auto"}
          >
            helpful
          </button>

          <a href="#" className={"common-link review__report-abuse-link"}>
            Report abuse
          </a>
        </div>
      </div>

      <div className="review__divider reviews-divider reviews-divider" />
    </div>
  );
};

export default Review;
