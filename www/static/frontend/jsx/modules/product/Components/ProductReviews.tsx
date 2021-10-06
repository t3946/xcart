import React from "react";
import OverallRating from "@client/jsx/modules/shared/components/ratings/OverallRating";
import { getProductsRatingsAction } from "@client/jsx/redux/actions/RatingsActions";
import { useDispatch, useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import ArrowIconTablet from "@client/modules/icon/components/account/chevron-down/AccountSidebarTablet";
import { Collapse } from "react-bootstrap";
import classnames from "classnames";

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

  if (!ratings) {
    dispatch(getProductsRatingsAction({ data: { productId } }));
  }

  return (
    <div className={"product-reviews"}>
      <div className="row m-0">
        <div className="col product-reviews-left-column">
          <h3
            className={
              "product-reviews-header product-reviews-header__big product-reviews_column-header d-none d-lg-block"
            }
          >
            Customer reviews
          </h3>

          <h4
            className={"product-reviews_overall-header mb-1 mb-md-14 mb-lg-16"}
          >
            Overall
          </h4>

          <div className="overall-rating">
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

          <h4 className={"product-reviews-header mb-lg-3 mb-md-20"}>
            By feature
          </h4>

          <h4 className={"product-reviews-header mb-lg-3 mb-md-20"}>
            Review this product
          </h4>
        </div>

        <div className="col-12 col-lg product-reviews-right-column">
          <h3
            className={
              "product-reviews-header product-reviews-header__big product-reviews_column-header mb-md-20"
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
