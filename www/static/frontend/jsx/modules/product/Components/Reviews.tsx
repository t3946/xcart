import {FormSelect} from "@client/modules/account/components/shared/FormSelect";
import React from "react";
import Review from "@client/modules/product/Components/Review/Review";
import {useDispatch} from "react-redux";
import ReviewSkeleton from "@client/modules/product/Components/Review/ReviewSkeleton";
import {
  getReviewsAction,
  addReviewsAction,
  clearReviewsAction,
} from "@client/jsx/redux/actions/ProductActions";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import classnames from "classnames";
import useSelectorAccount from "@client/modules/account/hooks/useSelectorAccount";

interface IProps {
  productId: number;
}

const Reviews: React.FC<any> = function (props: IProps) {
  const {productId} = props;
  const site = useSelectorAccount((e) => e.site);
  const dispatch = useDispatch();
  const LastReviewRef = React.useRef<any>();
  const ReviewsContainerRef = React.useRef<any>();
  const reviewsInfo = useSelectorAccount((e) => e.productsReviews[productId]);
  const reviews = reviewsInfo?.reviews || [];
  const country = reviewsInfo?.country;
  const totalReviews = reviewsInfo ? reviewsInfo.total : 0;
  const reviewsPerOnePage = 3;
  const [currentPage, setCurrentPage] = React.useState(0);
  const [isAllLoaded, setIsAllLoaded] = React.useState(false);
  const [isLoading, setIsLoading] = React.useState(false);
  const orders = site?.reviews?.orders || null;
  const [sort, setSort] = React.useState(orders ? orders[0] : {
    previewValue: "Most recent",
    viewValue: "Most recent",
    value: "most-recent",
  });
  const breakpoint = useBreakpoint();
  const [isIntersecting, setIsIntersecting] = React.useState(false);


  //load reviews
  //can load
  if (!isAllLoaded && !isLoading) {
    //should load first items
    if (reviews.length === 0 && totalReviews > 0) {
      setIsLoading(true);
      getMoreReviews();
    }
    //should load additional items
    else if (isIntersecting) {
      setIsLoading(true);
      setIsIntersecting(false);
      getMoreReviews();
    }
  }

  //update all reviews loaded flag
  if (reviewsInfo && totalReviews === reviews.length && !isAllLoaded) {
    setIsAllLoaded(true);
  }

  function reviewsTemplate() {
    const reviewsTemplates = [];

    if (reviews) {
      for (let i = 0; i < reviews.length; i++) {
        if (i + 1 === reviews.length) {
          reviewsTemplates.push(<Review review={reviews[i]} ref={LastReviewRef}/>);
        } else {
          reviewsTemplates.push(<Review review={reviews[i]}/>);
        }
      }
    }

    if (isLoading) {
      const loadedReviewsNumber = reviews ? reviews.length : 0;
      const lastReviews = totalReviews - loadedReviewsNumber;
      const skeletonsNumber = Math.min(lastReviews, reviewsPerOnePage);

      for (let i = 0; i < skeletonsNumber; i++) {
        reviewsTemplates.push(<ReviewSkeleton/>);
      }
    }

    return reviewsTemplates;
  }

  function getMoreReviews() {
    const maxLimit = totalReviews - reviews.length
    const limit = Math.min(reviewsPerOnePage, maxLimit);
    const offset = reviewsPerOnePage * currentPage;

    if (limit === 0) {
      console.warn("Excess loading");
      setIsLoading(false);
      return;
    }

    dispatch(
      getReviewsAction({
        data: {
          limit,
          offset,
          productId: props.productId,
          sort: sort.value,
        },

        success(res) {
          if (offset + limit === totalReviews) {
            setIsAllLoaded(true);
          }

          setIsLoading(false);
          setCurrentPage(currentPage + 1);

          dispatch(
            addReviewsAction({
              productId: props.productId,
              reviews: res.reviews,
              country: res.country,
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

  function hatTemplate() {
    if (totalReviews === 0) {
      return "No reviews";
    }

    return (
      <>
        <span className={"d-none d-md-block"}>
          {sort.previewValue} from the {country}
        </span>

        <FormSelect
          items={orders}
          onClick={changeSorting}
          name={"select-sort"}
          value={sort}
          classes={{group: "product-reviews-filter-select mb-20 mb-md-0"}}
        />

        <span className={"d-md-none"}>
          {sort.previewValue} from the {country}
        </span>
      </>
    );
  }

  const classes = {
    hat: [
      "product-reviews-header",
      "product-reviews-header_big",
      "product-reviews_column-header",
      "mb-md-20",
      "d-md-flex",
      "justify-content-between",
      "align-items-center",
      {"skeleton-box": totalReviews > 0 && !country},
    ],
    container: [
      "reviews-container",
      "product-reviews__reviews-container",
      "common-scrollbar",
      {
        "d-none": totalReviews === 0,
      },
    ],
  };

  function changeSorting(item) {
    setSort(item);
    dispatch(clearReviewsAction({productId: props.productId}));
    setCurrentPage(0);
    setIsAllLoaded(false);
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

            if (isIntersecting !== entry.isIntersecting) {
              setIsIntersecting(entry.isIntersecting);
            }

            if (entry.isIntersecting) {
              observer.unobserve(target);
            }
          });
        }, options);

        if (!isLoading && !isAllLoaded && !isIntersecting) {
          reviewLoadedObserver.observe(target);
        }
      },
    });

    return function () {
      if (reviewLoadedObserver && target) {
        reviewLoadedObserver.unobserve(target);
      }
    };
  });

  return (
    <>
      <h3 className={classnames(classes.hat)}>{hatTemplate()}</h3>

      <div
        className={classnames([
          "reviews-wrapper",
          {"overflow-hidden": reviews && reviews.length === 0},
        ])}
      >
        <div
          className={classnames(classes.container)}
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
