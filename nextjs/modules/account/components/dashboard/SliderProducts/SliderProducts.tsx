import { Swiper, SwiperSlide } from "swiper/react";
import SwiperCore, { Lazy, Scrollbar } from "swiper";
import cn from "classnames";
import CardSkeleton from "@modules/account/components/dashboard/SliderProducts/CardSkeleton";
import CartProduct from "@modules/components/product/card/slider/Card";
import React from "react";
import Styles from "@modules/account/components/dashboard/SliderProducts/SliderProducts.module.scss";

SwiperCore.use([Lazy, Scrollbar]);

const SliderProducts: React.FC<any> = function (props) {
  const { url } = props;
  const [paginationPage, setPaginationPage] = React.useState(1);
  const [items, setItems] = React.useState<Record<any, any>[]>([]);
  const [swiperObject, setSwiperObject] = React.useState<SwiperCore>();
  const [isLoading, setIsLoading] = React.useState(false);
  const [isAllLoaded, setIsAllLoaded] = React.useState(false);
  const [isReachBegin, setIsReachBegin] = React.useState(false);
  const [isReachEnd, setIsReachEnd] = React.useState(false);
  const navStep = 3;

  function goTo(step: number) {
    if (!swiperObject) {
      return;
    }

    const newRealIndex = swiperObject.realIndex + step;
    swiperObject.slideToLoop(newRealIndex);
  }

  function goPrev() {
    goTo(-navStep);
  }

  function goNext() {
    goTo(navStep);
  }

  function loadNewItems() {
    if (isLoading) {
      return;
    }

    if (isAllLoaded) {
      return;
    }

    setIsLoading(true);

    fetch(`/api${url}?page=${paginationPage}`, {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((res) => res.json())
      .then((res) => {
        // if all product was loaded then disable this function
        if (res.pager.currentPage === res.pager.pagesCount) {
          setIsAllLoaded(true);
        }

        setItems([...items, ...res.items]);
        setIsLoading(false);
        setPaginationPage(paginationPage + 1);
      });
  }

  function itemsTemplate() {
    const slides = [];

    for (let i = 0; i < items.length; i++) {
      const product = items[i];

      slides.push(
        <SwiperSlide className="products-slider-slide" key={`product-${i}`}>
          <CartProduct product={product} />
        </SwiperSlide>
      );
    }

    if (isLoading) {
      for (let i = 1; i <= 10; i++) {
        slides.push(
          <SwiperSlide
            className="products-slider-slide"
            key={`product-skeleton-${i}`}
          >
            <CardSkeleton key={`product-skeleton-${i}`} />
          </SwiperSlide>
        );
      }
    }

    return slides;
  }

  return (
    <Swiper
      spaceBetween={0}
      longSwipesRatio={0.05}
      width={170}
      slidesPerView={1}
      lazy={true}
      className="products-slider"
      scrollbar={{
        el: ".products-slider-scrollbar",
        draggable: true,
        hide: false,
        dragClass: cn(Styles.swiperScrollbarDrag),
      }}
      breakpoints={{
        171: {
          width: 2 * 172,
          slidesPerView: 2,
        },
        340: {
          width: 3 * 172,
          slidesPerView: 3,
        },
        500: {
          width: 4 * 172,
          slidesPerView: 4,
        },
        680: {
          width: 5 * 172,
          slidesPerView: 5,
        },
        860: {
          width: 5 * 206,
        },
        1024: {
          width: 6 * 206,
          slidesPerView: 6,
        },
      }}
      onSwiper={(swiper) => {
        setSwiperObject(swiper);
      }}
      onReachEnd={loadNewItems}
      onSlideChange={() => {
        if (!swiperObject) {
          return;
        }

        setIsReachBegin(swiperObject.isBeginning);
        setIsReachEnd(swiperObject.isEnd);
      }}
    >
      {itemsTemplate()}

      <div
        className={cn(
          "products-slider-left",
          "products-slider-nav",
          "show-for-medium",
          isReachBegin && "products-slider-nav__inactive"
        )}
        onClick={goPrev}
      />

      <div
        className={cn(
          "products-slider-right",
          "products-slider-nav",
          "show-for-medium",
          isReachEnd && "products-slider-nav__inactive"
        )}
        onClick={goNext}
      />
      <div className="products-slider-scrollbar" />
    </Swiper>
  );
};

export default SliderProducts;
