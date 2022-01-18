import React from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import classnames from "classnames";

interface IProps {
  items: Record<any, any>[];
  index: number;
  slideTo: (index: number) => void;
}

const SliderThumbs: React.FC<IProps> = function (
  props: IProps
) {
  const { items, index, slideTo } = props;
  const buttonStyles = {
    width: "100%",
  };
  const IMAGE_TYPE = "image";
  const VIDEO_TYPE = "video";
  const VIDEO_PROVIDER_VIMEO = "vimeo";
  const VIDEO_PROVIDER_YOUTUBE = "youtube";

  const classes = {
    navButton: [
      {
        "d-none": items.length < 2,
      },
    ],
    navButtonPrev: ["prev", "product-thumbs-slider-prev"],
    navButtonNext: ["next", "product-thumbs-slider-next"],
  };

  function prev() {
    const index = (props.index || items.length) - 1;

    slideTo(index);
  }

  function next() {
    slideTo((index + 1) % items.length);
  }

  function thumbsTemplates() {
    const thumbs = [];

    for (let i = 0; i < items.length; i++) {
      const item = items[i];
      const type = item.type;
      const isActive = index === i;
      const style: React.CSSProperties = {};
      const classes = [
        "slide",
        "images-slider-thumb",
        {
          "type-image": type === IMAGE_TYPE,
          "type-video": type === VIDEO_TYPE,
          active: isActive,
        },
      ];

      switch (type) {
        case IMAGE_TYPE:
          style.backgroundImage = `url(${item.thumb})`;
          break;

        case VIDEO_TYPE:
          let src;

          switch (item.provider) {
            case VIDEO_PROVIDER_VIMEO:
            case VIDEO_PROVIDER_YOUTUBE:
              src = item.image_1;
              break;
          }

          if (src) {
            style.backgroundImage = `url(${src})`;
            classes.push("play-icon");
          }

          break;
      }

      thumbs.push(
        <SwiperSlide
          className={classnames(classes)}
          key={`image.thumb.${i}`}
          onClick={() => slideTo(i)}
          style={style}
        />
      );
    }

    return thumbs;
  }

  return (
    <div className="slider-thumbs d-none d-lg-flex">
      <button
        className={classnames(classes.navButton, classes.navButtonPrev)}
        onClick={prev}
        style={buttonStyles}
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="31.75"
          height="17.688"
          viewBox="0 0 31.75 17.688"
        >
          <path
            className="prev_path"
            d="M90.364,222.341l-0.728-.685,16-17,0.728,0.686Zm30.272,0,0.728-.685-16-17-0.728.686Z"
            transform="translate(-89.625 -204.656)"
          />
        </svg>
      </button>

      <Swiper
        spaceBetween={5}
        longSwipesRatio={0.05}
        slidesPerView={"auto"}
        effect={"coverflow"}
        direction={"vertical"}
        className={"product-thumbs-slider swiper-container w-100"}
        navigation={{
          nextEl: ".product-thumbs-slider-next",
          prevEl: ".product-thumbs-slider-prev",
        }}
      >
        {thumbsTemplates()}
      </Swiper>

      <button
        className={classnames(classes.navButton, classes.navButtonNext)}
        onClick={next}
        style={buttonStyles}
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="31.75"
          height="17.688"
          viewBox="0 0 31.75 17.688"
        >
          <path
            className="next_path"
            d="M120.636,279.657l0.728,0.685-16,17-0.728-.685Zm-30.272,0-0.728.685,16,17,0.728-.685Z"
            transform="translate(-89.625 -279.656)"
          />
        </svg>
      </button>
    </div>
  );
};

export default SliderThumbs;
