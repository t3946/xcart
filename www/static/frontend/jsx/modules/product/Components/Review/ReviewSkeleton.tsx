import React from "react";
import RatingStars from "@client/modules/shared/components/ratings/RatingStars";
import classnames from "classnames";
import { useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";

const ReviewSkeleton: React.FC = function () {
  const classes = {
    header: ["review__header", "review-header", "skeleton-box"],
  };

  function markAsHelpfulTemplate() {
    const user = useSelector((e: AccountStore) => e.user);

    if (!user) {
      return;
    }

    return (
      <div
        className={
          "d-flex flex-column flex-md-row align-items-center mx--10 mx-md-0 mt-3"
        }
      >
        <button className={"form-button w-100 w-md-auto skeleton-box"}>
          helpful
        </button>

        <a
          href="#"
          className={"common-link review__report-abuse-link skeleton-box"}
        >
          Report abuse
        </a>
      </div>
    );
  }

  return (
    <div className={"reviews__review review"}>
      <div className={"d-flex align-items-center"}>
        <span
          className={
            "review-avatar-image review__avatar skeleton-box d-inline-block"
          }
        />
        <span className="skeleton-box">User Name</span>
      </div>

      <div className={"d-md-flex align-items-center"}>
        <RatingStars rating={0} classes={{ container: "skeleton-box" }} />
        <h3 className={classnames(classes.header)}>review title</h3>
      </div>

      <div className={"review-location review__location"}>
        <div className={"review-gray-text skeleton-box"}>Review created</div>

        <span className={"review-verified-purchase skeleton-box"}>
          Verified Purchase
        </span>
      </div>

      <div className={"review-body"}>
        <div className="review-body-wrapper">
          <p className={"skeleton-box"}>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga
            libero minus praesentium quis repudiandae? Dolore eius ipsam itaque
            molestias quos soluta vel? Accusamus ducimus praesentium provident
            quos suscipit vero voluptas!
          </p>
        </div>
      </div>

      <div>
        <p className={"review-gray-text mt-2 mt-md-3 skeleton-box"}>
          helpful count
        </p>

        {markAsHelpfulTemplate()}
      </div>

      <div className="review__divider reviews-divider reviews-divider" />
    </div>
  );
};

export default ReviewSkeleton;
