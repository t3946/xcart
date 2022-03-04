import React from "react";
import RatingStars from "@client/modules/shared/components/ratings/RatingStars";
import classnames from "classnames";
import MarkAsHelpful from "@client/modules/product/Components/Review/MarkAsHelpful";
import HelpfulCount from "@client/modules/product/Components/Review/HelpfulCount";
import Body from "@client/modules/product/Components/Review/Body";
import { useSelector } from "react-redux";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import Files from "@client/jsx/modules/product/Components/Review/Files";
import dateTimeToDate from "@client/jsx/utils/dateTimeToDate";

interface IProps {
  review: {
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
    helpful_total: string;
    marked_helpful: boolean;
    verifiedPurchase: boolean;
    files: {
      images: Record<any, any>[];
      videos: Record<any, any>[];
    };
  }
}

const Review: React.FC<IProps> = function (props: IProps) {
  const rating = parseFloat(props.review.overall_rating);
  const classes = {
    header: [
      "review__header",
      "review-header",
      {
        "rating-star__red": rating > 0 && rating <= 2,
        "rating-star__yellow": rating > 2 && rating <= 3,
        "rating-star__green": rating > 3,
      },
    ],
  };
  const helpful_total = parseInt(props.review.helpful_total);

  function formatDate(dateTime: string): string {
    const dateObject = dateTimeToDate(dateTime);
    const date = dateObject.getDate();
    const month = dateObject.toLocaleDateString("en-US", { month: "long" });
    const year = dateObject.getFullYear();

    return `${month} ${date}, ${year}`;
  }

  function getLocation() {
    if (props.review.location) {
      return "in the " + props.review.location;
    }

    return "from the Earth";
  }

  function markAsHelpfulTemplate() {
    const user = useSelector((e: StoreInterface) => e.user);

    if (!user) {
      return;
    }

    return (
      <MarkAsHelpful
        isHelpful={props.review.marked_helpful}
        reviewId={parseInt(props.review.product_review_id)}
      />
    );
  }

  return (
    <div className={"reviews__review review"}>
      <div>
        <img
          src={"/" + props.review.user_avatar}
          alt={props.review.user_public_name}
          width={32}
          height={32}
          className={"review-avatar-image review__avatar"}
        />
        {props.review.user_public_name}
      </div>

      <div className={"d-md-flex align-items-center"}>
        <RatingStars rating={parseFloat(props.review.overall_rating)} />
        <h3 className={classnames(classes.header)}>{props.review.header}</h3>
      </div>

      <div className={"review-location review__location"}>
        <div className={"review-gray-text"}>
          Reviewed {getLocation()} on {formatDate(props.review.created)}
        </div>

        {props.review.verifiedPurchase && <span className={"review-verified-purchase"}>Verified Purchase</span>}
      </div>

      <Body content={props.review.body} />

      <Files files={props.review.files} />

      <div>
        <HelpfulCount count={helpful_total} />

        {markAsHelpfulTemplate()}
      </div>

      <div className="review__divider reviews-divider reviews-divider" />
    </div>
  );
};

export default Review;
