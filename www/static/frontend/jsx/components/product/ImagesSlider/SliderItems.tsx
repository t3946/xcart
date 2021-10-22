import React from "react";
import classnames from "classnames";
import map from "lodash/map";
import { SwiperSlide } from "swiper/react";
import Store from "@client/jsx/redux/stores/Store";
import $ from "jquery";
import {
  photoSwipeSetItemsAction,
  photoSwipeSetOptionIndexAction,
  photoSwipeSetThumbsInitiatorAction,
} from "@client/jsx/redux/actions/PhotoSwipeActions";

interface PropsInterface {
  items: Record<any, any>[];
  isVideo: boolean;
  openImageViewer: () => void;
}

const SliderItems = function (props: PropsInterface): Record<any, any>[] {
  const { openImageViewer } = props;

  let preparedItems = null;

  const items = map(props.items, (item: Record<any, any>, i) => {
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

    /**
     * view image in photo swipe image viewer
     */
    function zoomHandler(e, item, index?: number) {
      if (!preparedItems) {
        const items = [];

        for (const i in props.items) {
          const item = props.items[i];

          switch (item.type) {
            case IMAGE_TYPE:
              items.push({ src: item.src, w: item.width, h: item.height });
              break;

            case VIDEO_TYPE:
              items.push({
                originalItem: item,
                html: (
                  <div className="slide-wrapper slider-detail">
                    <div className="video-wrapper">{renderVideoItem(item)}</div>
                  </div>
                ),
                onTap: (item) => {
                  if (!item.videoShow) {
                    $(item.container).find(".video-wrapper")[0].innerHTML =
                      renderVideoItem(item.originalItem, true, true);
                  }

                  item.videoShow = true;
                },
                onBlur: (item) => {
                  if (item.container && item.videoShow) {
                    item.videoShow = false;
                    $(item.container).find(".video-wrapper")[0].innerHTML =
                      renderVideoItem(item.originalItem);
                  }
                },
              });
          }
        }

        preparedItems = items;
      }

      // todo: pswp

      // const activeImage = sliderImageRef.current.querySelector(
      //   ".swiper-slide-active img"
      // );
      const activeImage = null;

      Store.dispatch(photoSwipeSetThumbsInitiatorAction(activeImage));
      Store.dispatch(photoSwipeSetOptionIndexAction(index));
      Store.dispatch(photoSwipeSetItemsAction(preparedItems));
    }

    switch (item.type) {
      case IMAGE_TYPE:
        return (
          <SwiperSlide
            key={key}
            onClick={openImageViewer}
            className={"d-flex align-items-center justify-content-center"}
          >
            <img src={item.preview} alt="" className={"product-page-image"} />
          </SwiperSlide>
        );

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

        return (
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

  return items;
};

export default SliderItems;
