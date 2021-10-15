import { FormSelect } from "@client/modules/account/components/shared/FormSelect";
import React from "react";
import Review from "@client/modules/product/Components/Review/Review";
import { useDispatch, useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import ReviewSkeleton from "@client/modules/product/Components/Review/ReviewSkeleton";
import appData from "@client/jsx/utils/AppData";
import {
  getReviewsAction,
  addReviewsAction,
  clearReviewsAction,
} from "@client/jsx/redux/actions/ProductActions";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import AppData from "@client/jsx/utils/AppData";
import classnames from "classnames";

interface PropsInterface {
  productId: number;
}

const Reviews: React.FC<any> = function (props: PropsInterface) {
  const dispatch = useDispatch();
  const LastReviewRef = React.useRef<any>();
  const totalReviews = AppData.products[props.productId].total_reviews;
  const ReviewsContainerRef = React.useRef<any>();
  const reviews = useSelector((e: AccountStore) => e.productsReviews)[
    props.productId
  ];
  const reviewsPerOnePage = 3;
  const [currentPage, setCurrentPage] = React.useState(0);
  const [isAllLoaded, setIsAllLoaded] = React.useState(totalReviews === 0);
  const [isLoading, setIsLoading] = React.useState(false);
  const orders = appData.reviews.orders;
  const [sort, setSort] = React.useState(orders[0]);
  const breakpoint = useBreakpoint();
  const [isIntersecting, setIsIntersecting] = React.useState(false);

  //load first reviews
  if (!isAllLoaded && !isLoading) {
    if (!reviews || isIntersecting) {
      getMoreReviews();
      setIsIntersecting(false);
    }
  }

  //update all reviews loaded flag
  if (reviews && totalReviews === reviews.length && !isAllLoaded) {
    // console.log("SET setIsAllLoaded true");
    setIsAllLoaded(true);
  }

  function reviewsTemplate() {
    const reviewsTemplates = [];

    if (reviews) {
      for (let i = 0; i < reviews.length; i++) {
        if (i + 1 === reviews.length) {
          reviewsTemplates.push(<Review {...reviews[i]} ref={LastReviewRef} />);
        } else {
          reviewsTemplates.push(<Review {...reviews[i]} />);
        }
      }
    }

    if (isLoading) {
      const loadedReviewsNumber = reviews ? reviews.length : 0;
      const lastReviews = totalReviews - loadedReviewsNumber;
      const skeletonsNumber = Math.min(lastReviews, reviewsPerOnePage);

      for (let i = 0; i < skeletonsNumber; i++) {
        reviewsTemplates.push(<ReviewSkeleton />);
      }
    }

    return reviewsTemplates;
  }

  function getMoreReviews() {
    // console.log("getMoreReviews");
    setIsLoading(true);

    dispatch(
      getReviewsAction({
        data: {
          limit: reviewsPerOnePage,
          offset: reviewsPerOnePage * currentPage,
          productId: props.productId,
          sort: sort.value,
        },

        success(res) {
          setIsLoading(false);
          setCurrentPage(currentPage + 1);

          dispatch(
            addReviewsAction({
              productId: props.productId,
              reviews: res,
            })
          );
        },
      })
    );
  }

  function seeMoreReviewsTemplate() {
    if (isAllLoaded) {
      return;
    }

    return (
      <div className="product-reviews__see-more-reviews d-lg-none">
        <button
          className={"form-button form-button__outline"}
          onClick={getMoreReviews}
        >
          See more reviews
        </button>
      </div>
    );
  }

  React.useEffect(function () {
    let reviewLoadedObserver = null;
    let target = null;

    if (!LastReviewRef.current?.base || isAllLoaded) {
      return;
    }

    breakpoint({
      lg: function () {
        target = LastReviewRef.current.base;

        const options = {
          root: ReviewsContainerRef.current.base,
          rootMargin: "0px",
          threshold: 0.75,
        };

        reviewLoadedObserver = new IntersectionObserver((entries, observer) => {
          entries.forEach((entry) => {
            setIsIntersecting(entry.isIntersecting);

            if (entry.isIntersecting) {
              // console.log("isIntersecting load product");
              observer.unobserve(target);
              // getMoreReviews();
            }
          });
        }, options);

        reviewLoadedObserver.observe(target);
      },
    });

    return function () {
      if (reviewLoadedObserver && target) {
        reviewLoadedObserver.unobserve(target);
      }
    };
  });

  console.log({
    sort,
    currentPage,
  });

  return (
    <>
      <h3
        className={
          "product-reviews-header product-reviews-header_big product-reviews_column-header mb-md-20 d-flex justify-content-between align-items-center"
        }
      >
        <span>Top reviews from the United States</span>

        <FormSelect
          items={orders}
          onClick={(item) => {
            setSort(item);
            dispatch(clearReviewsAction({ productId: props.productId }));
            setCurrentPage(0);
            setIsAllLoaded(false);
          }}
          name={"select-sort"}
          value={sort}
          classes={{ group: "w-auto" }}
        />
      </h3>

      <div
        className={classnames([
          "reviews-wrapper",
          { "overflow-hidden": reviews && reviews.length === 0 },
        ])}
      >
        <div
          className="reviews-container product-reviews__reviews-container common-scrollbar"
          ref={ReviewsContainerRef}
        >
          {reviewsTemplate()}
        </div>
      </div>

      {seeMoreReviewsTemplate()}
    </>
  );
};

export default Reviews;
