import React from "react";
import OverallRating from "@client/jsx/modules/shared/components/ratings/OverallRating";
import { getProductsRatingsAction } from "@client/jsx/redux/actions/RatingsActions";
import { useDispatch, useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import ArrowIconTablet from "@client/modules/icon/components/account/chevron-down/AccountSidebarTablet";
import { Collapse } from "react-bootstrap";
import classnames from "classnames";
import RatingStars from "@client/jsx/modules/shared/components/ratings/RatingStars";

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
      "how-calculated-icon",
      {
        "how-calculated-icon__flip": isVisibleHowCalculated,
      },
    ],
  };

  //current page product id
  const productId = parseInt(
    document.location.pathname.match(/\/product\/(\d+)/)[1]
  );

  const ratings = useSelector((e: AccountStore) => e.productsRatings)[
    productId
  ];

  let totalRatingsNumber = 0;

  if (ratings) {
    if (ratings.overall !== null) {
      totalRatingsNumber = ratings.overall.rates.reduce(
        (pv, cv) => pv + parseInt(cv.totalRates),
        0
      );
    }
  } else {
    dispatch(getProductsRatingsAction({ data: { productId } }));
  }

  function featureRatingsTemplate() {
    const ratingElements = [];

    if (ratings) {
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
      <ul className={"product-rating list-unstyled m-0"}>{ratingElements}</ul>
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
                className={"how-calculated-arm d-inline-block"}
                onClick={() =>
                  setIsVisibleHowCalculated(!isVisibleHowCalculated)
                }
              >
                <ArrowIconTablet
                  className={classnames(classes.howCalculatedClasses)}
                />{" "}
                How are ratings calculated ?
              </div>

              <Collapse in={isVisibleHowCalculated}>
                <p className={"how-calculated_text"}>
                  To calculate the overall star rating and percentage breakdown
                  by star, we don’t use a simple average. Instead, our system
                  considers things like how recent a review is and if the
                  reviewer bought the item on S3 stores. It also analyzes
                  reviews to verify trustworthiness.
                </p>
              </Collapse>
            </div>
          </div>

          <div className="product-reviews__divider reviews-divider reviews-divider_theme_dark" />

          <h4 className={"product-reviews-header mb-2 mb-lg-3 mb-md-20"}>
            By feature
          </h4>

          {featureRatingsTemplate()}

          <div className="product-reviews__divider reviews-divider reviews-divider_theme_dark" />

          <h4 className={"product-reviews-header mb-2 mb-lg-3 mb-md-20"}>
            Review this product
          </h4>

          <p className={"product-reviews__share-your-thoughts"}>
            Share your thoughts with other customers
          </p>

          <div className="d-flex justify-content-center">
            <button className="mx--10 m-md-0 form-button w-100 w-md-auto w-lg-100 p-lg-0">
              write a customer review
            </button>
          </div>
        </div>

        <div className="col-12 col-lg product-reviews-right-column">
          <h3
            className={
              "product-reviews-header product-reviews-header_big product-reviews_column-header mb-md-20"
            }
          >
            Top reviews from the United States
          </h3>
        </div>
      </div>
    </div>
  );
};

export default ProductReviews;
