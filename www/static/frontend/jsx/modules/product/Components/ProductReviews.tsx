import React from "react";
import TooltipRatingContent from "@client/jsx/modules/account/components/lists/TooltipRatingContent";
import { getProductsRatingsAction } from "@client/jsx/redux/actions/RatingsActions";
import { useDispatch, useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";

const ProductReviews: React.FC = function () {
  const dispatch = useDispatch();
  const classes = {
    overallRating: {
      rating: {
        icon: "review-overall-rating-star",
        container: "review-overall-rating-container",
      },
    },
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

  function ratingTemplates() {
    if (!ratings) {
      return;
    }

    return (
      <div className="overall-rating">
        <TooltipRatingContent
          minRating={ratings.minRating}
          maxRating={ratings.maxRating}
          ratings={ratings.overallRatings}
          classes={classes.overallRating}
        />
      </div>
    );
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
          {ratingTemplates()}
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
