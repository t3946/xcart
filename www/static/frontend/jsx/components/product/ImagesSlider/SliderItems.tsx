import React from "react";
import classnames from "classnames";
import { SwiperSlide } from "swiper/react";

interface PropsInterface {
  items: Record<any, any>[];
  isVideo: boolean;
  openImageViewer: () => void;
}

const SliderItems = function (props: PropsInterface): Record<any, any>[] {
  const { items, openImageViewer } = props;

  const sliderItems = [];

  items.forEach((item: Record<any, any>, i) => {
    const position = i + 1;
    const key = "detail." + position;
    const IMAGE_TYPE = "image";
    const VIDEO_TYPE = "video";

    function renderVideoItem(item, forceVideo = false, autoplay = true) {
      if (item.meta.type === "youtube") {
        if (props.isVideo || forceVideo) {
          return (
            <iframe
              src={
                item.meta.p +
                "www.youtube.com/embed/" +
                item.meta.id +
                "?autoplay=" +
                (autoplay ? 1 : 0)
              }
              frameBorder="0"
              width={640}
              height={360}
              allowFullScreen
            />
          );
        } else {
          return renderImage(item.img || item.meta.images.img, "play-icon");
        }
      }
    }

    function renderImage(src, classes = "") {
      return (
        <div
          className={"image " + classes}
          style={{ backgroundImage: `url(${src})` }}
        />
      );
    }

    switch (item.type) {
      case IMAGE_TYPE:
        sliderItems.push(
          <SwiperSlide
            key={key}
            onClick={openImageViewer}
            className={"d-flex align-items-center justify-content-center"}
          >
            <img src={item.preview} alt="" className={"product-page-image"} />
          </SwiperSlide>
        );
        break;

      case VIDEO_TYPE:
        const content = renderVideoItem(item);
        const classes = [
          "slide",
          "type-video",
          {
            "video-show": props.isVideo,
            "video-hide": !props.isVideo,
          },
        ];

        sliderItems.push(
          <div
            className={classnames(classes)}
            onClick={openImageViewer}
            key={key}
          >
            {content}
          </div>
        );
    }
  });

  return sliderItems;
};

export default SliderItems;
