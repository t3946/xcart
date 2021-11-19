import React from "react";
import { SwiperSlide } from "swiper/react";

interface IProps {
  items: Record<any, any>[];
  isVideo: boolean;
  openImageViewer: () => void;
}

const SliderItems = function (props: IProps): Record<any, any>[] {
  const { items, openImageViewer } = props;

  const sliderItems = [];

  items.forEach((item: Record<any, any>, i) => {
    const position = i + 1;
    const key = "detail." + position;
    const IMAGE_TYPE = "image";
    const VIDEO_TYPE = "video";
    const VIDEO_PROVIDER_VIMEO = "vimeo";
    const VIDEO_PROVIDER_YOUTUBE = "youtube";

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
        let src;

        switch (item.provider) {
          case VIDEO_PROVIDER_VIMEO:
          case VIDEO_PROVIDER_YOUTUBE:
            src = item.image_2;
            break;
        }

        sliderItems.push(
          <SwiperSlide
            key={key}
            onClick={openImageViewer}
            className={
              "d-flex align-items-center justify-content-center play-icon"
            }
          >
            <img src={src} alt="" className={"product-page-image"} />
          </SwiperSlide>
        );
    }
  });

  return sliderItems;
};

export default SliderItems;
