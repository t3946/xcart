import { Swiper, SwiperSlide } from "swiper/react";
import SwiperCore, { Lazy, Scrollbar } from "swiper";
import Image from "./components/Image";
import SliderProducts from "@/components/Sliders/SliderProducts/SliderProducts";
import classnames from "classnames";

SwiperCore.use([Lazy, Scrollbar]);

export default class SliderProductsMini extends SliderProducts {
  constructor(props) {
    super(props);
  }

  navStep() {
    return 5;
  }

  render(props, state, context) {
    const { error, isLoaded, items, isBeginning, isEnd } = this.state;

    $(this.base).parents(".slider-block").removeClass("hide");

    if (error) {
      // hide slider when error
      $(this.base).parents(".slider-block").addClass("hide");
      return <div>Error: {JSON.stringify(error)}</div>;
    } else if (!isLoaded) {
      // place-holder element
      return <div style="height: 120px"></div>;
    } else {
      return (
        <Swiper
          spaceBetween={0}
          longSwipesRatio={0.05}
          width={3 * 90}
          slidesPerView={3}
          lazy={true}
          className="products-slider"
          scrollbar={{
            el: ".products-slider-scrollbar",
            draggable: true,
            hide: false,
          }}
          breakpoints={{
            768: {
              width: 8 * 90,
              slidesPerView: 8,
            },
            1024: {
              width: 14 * 90,
              slidesPerView: 14,
            },
          }}
          onSwiper={(swiper) => (this.swiperObject = swiper)}
          onSlideChange={this.updateSlideBordersFlags}
          onReachEnd={this.onReachEndHandler}
        >
          {items.map((item, i) => (
            <SwiperSlide
              className="products-slider-slide products-slider-slide__mini"
              key={i}
            >
              {/*image*/}
              <a
                href={item.url}
                title={item.name}
                className="products-slider-image-link"
              >
                <Image {...item} />
              </a>
            </SwiperSlide>
          ))}

          <div
            className={classnames(
              "products-slider-left",
              "products-slider-nav",
              "show-for-medium",
              isBeginning && "products-slider-nav__inactive"
            )}
            onClick={this.goPrev}
          />
          <div
            className={classnames(
              "products-slider-right",
              "products-slider-nav",
              "show-for-medium",
              isEnd && "products-slider-nav__inactive"
            )}
            onClick={this.goNext}
          />
          <div className="products-slider-scrollbar" />
        </Swiper>
      );
    }
  }
}
