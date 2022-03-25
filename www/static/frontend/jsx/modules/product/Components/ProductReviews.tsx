import React from "react";
import OverallRating from "@client/jsx/modules/shared/components/ratings/OverallRating";
import { getRatingsAndReviewsAction } from "@client/jsx/redux/actions/ProductActions";
import { useDispatch } from "react-redux";
import ArrowIcon from "@client/modules/icon/components/account/chevron-down/AccountSidebarTablet";
import { Collapse } from "react-bootstrap";
import classnames from "classnames";
import RatingStars from "@client/jsx/modules/shared/components/ratings/RatingStars";
import Reviews from "@client/modules/product/Components/Reviews";
import useSelectorAccount from "@client/modules/account/hooks/useSelectorAccount";

const WriteAReviewButton: React.FC = function () {
  const user = useSelectorAccount((e) => e.user);
  const product = useSelectorAccount((e) => e.product);
  const productId = product.productid;
  const href = user
    ? `/account/create-review/${productId}`
    : "/account/login?page=" +
      encodeURIComponent(`/create-review/${productId}`);

  return (
    <a
      className="d-flex justify-content-center text-decoration-none"
      href={href}
    >
      <button className="mx--10 m-md-0 form-button w-100 w-md-auto w-lg-100 p-lg-0">
        <span className={"d-none d-md-block"}>write a customer review</span>
        <span className={"d-md-none"}>write a review</span>
      </button>
    </a>
  );
};

const ProductReviews: React.FC = function () {
  const dispatch = useDispatch();
  const [isVisibleHowCalculated, setIsVisibleHowCalculated] =
    React.useState(false);
  const classes = {
    overallRating: {
      rating: {
        icon: "review-overall-rating-star",
        container: "review-overall-rating-container",
      },
    },
    howCalculatedClasses: [
      "spoiler-icon_arrow",
      "me-1",
      {
        "spoiler-icon_flip": isVisibleHowCalculated,
      },
    ],
  };

  //current page product id
  const productId = parseInt(
    document.location.pathname.match(/\/product\/(\d+)/)[1]
  );

  const ratings = useSelectorAccount((e) => e.productsRatings)[productId];
  const isUserCanWriteReview = useSelectorAccount((e) => e.productPage.isUserCanWriteReview);


  let totalRatingsNumber = 0;

  if (ratings) {
    if (ratings.overall !== null) {
      totalRatingsNumber = ratings.overall.rates.reduce(
        (pv, cv) => pv + parseInt(cv.totalRates),
        0
      );
    }
  } else {
    dispatch(
      getRatingsAndReviewsAction({ data: { productId, limit: 3, offset: 0 } })
    );
  }

  function featureRatingsTemplate() {
    const ratingElements = [];

    if (ratings) {
      if (ratings.features.length === 0) {
        return null;
      }

      for (let i = 0; i < ratings.features.length; i++) {
        const { rating } = ratings.features[i];
        const total = parseInt(ratings.features[i].total);

        ratingElements.push(
          <li className={"feature-rating-list__item d-flex align-items-center"}>
            <span className={"feature-rating-name flex-grow-1"}>
              {rating.name}
            </span>

            <RatingStars
              classes={{
                container: "flex-grow-0 feature-rating-stars",
                icon: "feature-rating-star",
              }}
              rating={total}
            />

            <span className={"feature-rating-value text-end"}>
              {total.toFixed(1)}
            </span>
          </li>
        );
      }
    } else {
      //print skeleton
      const skeletonRatingsNumber = 3;

      for (let i = 0; i < skeletonRatingsNumber; i++) {
        ratingElements.push(
          <li
            className={
              "feature-rating-list__item d-flex align-items-center feature-rating-list-item_skeleton skeleton-box"
            }
          />
        );
      }
    }

    return (
      <>
        <h4 className={"product-reviews-header mb-2 mb-lg-3 mb-md-20"}>
          By feature
        </h4>

        <ul className={"product-rating list-unstyled m-0"}>{ratingElements}</ul>
      </>
    );
  }

  function writeAReviewTemplate() {
    if (!isUserCanWriteReview) {
      return null;
    }

    return (
      <>
        <div className="product-reviews__divider reviews-divider reviews-divider_theme_dark" />

        <h4 className={"product-reviews-header mb-2 mb-lg-3 mb-md-20"}>
          Review this product
        </h4>

        <p className={"product-reviews__share-your-thoughts"}>
          Share your thoughts with other customers
        </p>

        <WriteAReviewButton />
      </>
    );
  }

  function overallRatingTemplate() {
    if (totalRatingsNumber === 0) {
      return;
    }

    return (
      <>
        <h4
          className={
            "product-reviews-header mb-2 mb-md-14 mb-lg-16 d-flex align-items-center justify-content-between"
          }
        >
          Overall
          <span className={"overall-header-total d-lg-none"}>
            {totalRatingsNumber.toLocaleString()} Ratings
          </span>
        </h4>

        <div className="product-rating">
          <OverallRating
            ratings={ratings?.overall}
            classes={classes.overallRating}
          />

          <div className="how-calculated product-reviews_how-calculated">
            <div
              className={"common-link common-link_spoiler d-inline-block"}
              onClick={() => setIsVisibleHowCalculated(!isVisibleHowCalculated)}
            >
              <div className="d-flex align-items-center">
                <ArrowIcon
                  className={classnames(classes.howCalculatedClasses)}
                />{" "}
                How are ratings calculated ?
              </div>
            </div>

            <Collapse in={isVisibleHowCalculated}>
              <p className={"how-calculated_text"}>
                To calculate the overall star rating and percentage breakdown by
                star, we don’t use a simple average. Instead, our system
                considers things like how recent a review is and if the reviewer
                bought the item on S3 stores. It also analyzes reviews to verify
                trustworthiness.
              </p>
            </Collapse>
          </div>
        </div>

        <div className="product-reviews__divider reviews-divider reviews-divider_theme_dark" />
      </>
    );
  }

  return (
    <div className={"product-reviews"}>
      <div className="row m-0">
        <div className="col product-reviews-left-column">
          <h3
            className={
              "product-reviews-header product-reviews-header_big product-reviews_column-header d-none d-lg-block"
            }
          >
            Customer reviews
          </h3>

          {overallRatingTemplate()}

          {featureRatingsTemplate()}

          {writeAReviewTemplate()}
        </div>

        <div className="col-12 col-lg product-reviews-right-column">
          <Reviews productId={productId} />
        </div>
      </div>
    </div>
  );
};

export default ProductReviews;
