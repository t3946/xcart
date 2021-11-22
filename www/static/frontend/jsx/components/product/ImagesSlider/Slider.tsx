import React from "react";
import uuid from "react-uuid";
import { Swiper } from "swiper/react";
import SliderItems from "@client/jsx/components/product/ImagesSlider/SliderItems";
import SliderThumbs from "@client/jsx/components/product/ImagesSlider/SliderThumbs";
import {
  photoSwipeSetItemsAction,
  photoSwipeSetThumbInitiatorAction,
  photoSwipeSetOwnerIdAction,
  photoSwipeSetOptionIndexAction,
} from "@client/jsx/redux/actions/PhotoSwipeActions";
import { useDispatch, useSelector } from "react-redux";
import StoreInterface from "@client/modules/account/ts/types/store.type";

interface ItemsInterface {
  type: string;
  src: string;
  width: number;
  height: number;
}

interface IProps {
  items: ItemsInterface[];
}

const Slider: React.FC<IProps> = function (props: IProps) {
  const [componentId] = React.useState(uuid());
  const [isVideo, setIsVideo] = React.useState(false);
  const [index, setIndex] = React.useState(0);
  const { items } = props;
  const [productImagesSlider, setProductImagesSlider] = React.useState(null);
  const dispatch = useDispatch();
  const photoSwipe = useSelector((e: StoreInterface) => e.photoswipe);

  // do if image viewer photo swipe now works for this slider
  function syncWithImageViewer() {
    if (photoSwipe.ownerId !== componentId) {
      return;
    }

    // sync viewer and slider
    if (photoSwipe.index !== undefined && photoSwipe.index !== index) {
      slideTo(photoSwipe.index);
    }

    const activeImageElement = getActiveImageElement();

    if (activeImageElement !== photoSwipe.thumb) {
      dispatch(photoSwipeSetThumbInitiatorAction(activeImageElement));
    }
  }

  // get current image element from images slider
  function getActiveImageElement(): HTMLElement | null {
    if (!productImagesSlider) {
      return null;
    }

    const slides = productImagesSlider.slides;
    const index = productImagesSlider.activeIndex;

    return slides[index].firstChild;
  }

  function videoItemTemplate(item) {
    return `<div class="wrapper">
        <div class="video-wrapper">
          <iframe
            class="pswp__video"
            width="960"
            height="640"
            src=${item.video}
            allowFullScreen
            allow="autoplay"
          />
        </div>
      </div>`;
  }

  /**
   * get photo swipe items array from swiper items
   */
  function getPhotoSwipeItems(): Record<any, any>[] {
    const IMAGE_TYPE = "image";
    const VIDEO_TYPE = "video";
    const photoSwipeItems = [];

    for (const i in items) {
      const item = items[i];

      switch (item.type) {
        case IMAGE_TYPE:
          photoSwipeItems.push({
            src: item.src,
            w: item.width,
            h: item.height,
          });
          break;

        case VIDEO_TYPE:
          photoSwipeItems.push({
            html: videoItemTemplate(item),
            w: 960,
            h: 640,
          });
          break;
      }
    }

    return photoSwipeItems;
  }

  function openImageViewer() {
    dispatch(photoSwipeSetOwnerIdAction(componentId));
    dispatch(photoSwipeSetItemsAction(getPhotoSwipeItems()));
    dispatch(photoSwipeSetOptionIndexAction(index));
    syncWithImageViewer();
  }

  function slideTo(index: number): void {
    setIndex(index);
    setIsVideo(items[index].type === "video");
  }

  // handle image slider change event
  function slideChangeHandler(swiper) {
    slideTo(swiper.realIndex);
    syncWithImageViewer();
  }

  syncWithImageViewer();

  //synchronize thumbs and images sliders
  if (productImagesSlider && index !== productImagesSlider.realIndex) {
    productImagesSlider.slideToLoop(index);
  }

  return (
    <div className="images-slider">
      <SliderThumbs items={items} index={index} slideTo={slideTo} />

      <Swiper
        spaceBetween={50}
        longSwipesRatio={0.05}
        slidesPerView={1}
        loop={true}
        effect={"coverflow"}
        className={"product-images-slider swiper-container"}
        onSwiper={setProductImagesSlider}
        onSlideChange={slideChangeHandler}
      >
        {SliderItems({
          items,
          isVideo,
          openImageViewer,
        })}
      </Swiper>
    </div>
  );
};

export default Slider;
