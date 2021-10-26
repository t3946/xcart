import PhotoSwipe from "@client/libs/photoswipe/dist/photoswipe";
import PhotoSwipeUI_Default from "@client/libs/photoswipe/dist/photoswipe-ui-default";
import React from "react";
import { useSelector, useDispatch } from "react-redux";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import {
  photoSwipeSetGalleryAction,
  photoSwipeClearAction,
  photoSwipeSetOptionIndexAction,
} from "@client/jsx/redux/actions/PhotoSwipeActions";
import classnames from "classnames";
import ArrowIcon from "@client/jsx/modules/icon/components/PhotoSwipe/Arrow";
import TimesIcon from "@client/jsx/modules/icon/components/PhotoSwipe/Times";

const PhotoSwipeContainer: React.FC = function () {
  const photoSwipeStore = useSelector((e: StoreInterface) => e.photoswipe);
  const { items, index } = photoSwipeStore;

  let gallery = photoSwipeStore.gallery;

  const dispatch = useDispatch();
  const containerRef = React.useRef<HTMLDivElement>();
  const prevButtonRef = React.useRef<HTMLDivElement>();
  const nextButtonRef = React.useRef<HTMLDivElement>();
  // remember how many was items even if that was cleared
  const [totalItems, setTotalItems] = React.useState(0);
  // change counter for force update component
  const [forceUpdate, setForceUpdate] = React.useState({ any: 0 });

  // update total items
  if (items && totalItems !== items.length) {
    setTotalItems(items.length);
  }

  const options: Record<any, any> = {
    index: index,
    speed: 300,
    bgOpacity: 0.91,
    zoomEl: false,
    maxSpreadZoom: 1,
    showHideOpacity: true,
    history: false,
  };

  if (photoSwipeStore.thumb || photoSwipeStore.thumbs) {
    const getThumbBoundsFn = function (index) {
      // if thumbs is not array then thumbs then there only one thumb
      const thumb = photoSwipeStore.thumb || photoSwipeStore.thumbs[index];

      const pageYScroll =
        window.pageYOffset || document.documentElement.scrollTop;
      const rect = thumb.getBoundingClientRect();

      return { x: rect.left, y: rect.top + pageYScroll, w: rect.width };
    };

    if (gallery) {
      gallery.options.getThumbBoundsFn = getThumbBoundsFn;
    } else {
      options.getThumbBoundsFn = getThumbBoundsFn;
    }
  }

  const classes: Record<any, any> = {
    navButtonContainer: ["product-photo-slider-button-container"],
    prevButtonContainer: ["photoswipe-left-arrow"],
    nextButtonContainer: ["photoswipe-right-arrow"],
    prevButtonIcon: ["photoswipe-left-arrow"],
    nextButtonIcon: ["photoswipe-right-arrow"],
  };

  if (totalItems <= 2) {
    classes.navButtonContainer.push({
      "d-none": totalItems <= 1,
    });

    const currentIndex = gallery ? gallery.getCurrentIndex() : 0;

    classes.prevButtonContainer.push({
      "photoswipe-arrow_disabled": currentIndex === 0,
    });

    classes.nextButtonContainer.push({
      "photoswipe-arrow_disabled": currentIndex === 1,
    });
  }

  // remove get params from url
  function removeUrlParams(url) {
    return url.split("?")[0];
  }

  /**
   * change prev nex buttons offsets
   */
  function changePadding(gallery): void {
    const container = gallery.currItem.container;

    if (!container) {
      return;
    }

    const currItem = gallery.currItem;
    const { fitRatio } = gallery.currItem;

    // slide is image
    if (currItem.src) {
      const w = currItem.w;
      const scaledWidth = Math.round(w * Math.min(fitRatio, 1));
      const offset = Math.ceil(scaledWidth / 2) + 50;

      prevButtonRef.current.style.paddingRight = `${offset}px`;
      nextButtonRef.current.style.paddingLeft = `${offset}px`;

      prevButtonRef.current.style.width = "50%";
      nextButtonRef.current.style.width = "50%";
    }
    // slide is video
    else if (currItem.html) {
      const frameWidth = 960;
      const buttonWidth = (window.innerWidth - frameWidth) / 2;
      const padding = 30;

      prevButtonRef.current.style.paddingRight = `${padding}px`;
      nextButtonRef.current.style.paddingLeft = `${padding}px`;

      prevButtonRef.current.style.width = `${buttonWidth}px`;
      nextButtonRef.current.style.width = `${buttonWidth}px`;
    }
  }

  /**
   * stop play for all videos exception current item video
   * @param stopAll if true then stop all videos without exception
   */
  function toggleVideoPlay(stopAll = false): void {
    const currentItemIframe =
      gallery.currItem.container.getElementsByTagName("iframe")[0];

    gallery.items.forEach((item) => {
      if (!item.container) {
        return;
      }

      const iframe: HTMLIFrameElement =
        item.container.getElementsByTagName("iframe")[0];

      if (iframe && (iframe !== currentItemIframe || stopAll === true)) {
        iframe.setAttribute("src", removeUrlParams(iframe.src));
      } else if (currentItemIframe && stopAll === false) {
        currentItemIframe.src =
          removeUrlParams(currentItemIframe.src) + "?autoplay=1&mute=1&muted=1";

        addEventListener(currentItemIframe, function () {
          console.log("ONLOAD SCRIPT");
        });
      }
    });
  }

  React.useEffect(() => {
    if (gallery || !items) {
      return;
    }

    gallery = new PhotoSwipe(
      containerRef.current,
      PhotoSwipeUI_Default,
      items,
      options
    );

    gallery.listen("afterInit", () => {
      document.body.style.overflow = "hidden";
      toggleVideoPlay();
    });

    // close gallery handler
    gallery.listen("close", () => {
      document.body.style.overflow = "initial";

      const item = gallery.currItem;

      if (item.onBlur) {
        item.onBlur(item, gallery);
      }

      toggleVideoPlay(true);
      dispatch(photoSwipeClearAction());
    });

    // correct image sizes
    gallery.listen("gettingData", (index, item) => {
      /**
       * if item has not image sizes then load image,
       * determine image sizes and save it in item
       */
      if (item.src && (item.w === null || item.h === null)) {
        // unknown size
        const img = new Image();

        img.onload = () => {
          item.w = img.width;
          item.h = img.height;

          gallery.invalidateCurrItems();
          gallery.updateSize(true);
        };

        img.src = item.src;
      }
    });

    gallery.listen("beforeChange", (d) => {
      if (!!d) {
        const pswp = gallery;
        const cIndex = pswp.getCurrentIndex();
        const prevItem = pswp.items[cIndex];

        if (prevItem.onBlur) {
          prevItem.onBlur(prevItem, gallery);
        }

        changePadding(gallery);
        toggleVideoPlay();
      }
    });

    gallery.listen("afterChange", () => {
      changePadding(gallery);
      setForceUpdate({ ...forceUpdate });
    });

    gallery.listen("itemChanged", () => {
      dispatch(photoSwipeSetOptionIndexAction(gallery.getCurrentIndex()));
    });

    gallery.listen("resize", () => {
      changePadding(gallery);
    });

    gallery.init();

    gallery.framework.bind(gallery.scrollWrap, "pswpTap", () => {
      const item = gallery.currItem;

      if (item.onTap) {
        item.onTap(item, gallery);
      }
    });

    changePadding(gallery);

    dispatch(photoSwipeSetGalleryAction(gallery));
  });

  return (
    <div
      className="pswp"
      tabIndex={-1}
      role="dialog"
      aria-hidden="true"
      ref={containerRef}
    >
      <div className="pswp__bg" />
      <div className="pswp__scroll-wrap">
        <div className="pswp__container">
          <div className="pswp__item" />
          <div className="pswp__item" />
          <div className="pswp__item" />
        </div>

        <div className="pswp__ui pswp__ui--hidden">
          <div className="pswp__top-bar">
            <div className="pswp__counter" />

            <button
              className="pswp__button pswp__button--close"
              title="Close (Esc)"
            >
              <svg
                className="pswp__button--close-icon"
                viewBox="0 0 30 29"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g>
                  <rect
                    width="1"
                    height="16"
                    transform="translate(20.3137 8) rotate(45)"
                  />
                  <rect
                    width="1"
                    height="16"
                    transform="translate(21.7279 19.3137) rotate(135)"
                  />
                </g>
              </svg>
            </button>

            <button
              className="pswp__button pswp__button--share"
              title="Share"
            />

            <button
              className="pswp__button pswp__button--fs"
              title="Toggle fullscreen"
            />

            <div className="pswp__preloader">
              <div className="pswp__preloader__icn">
                <div className="pswp__preloader__cut">
                  <div className="pswp__preloader__donut" />
                </div>
              </div>
            </div>
          </div>

          <div className="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
            <div className="pswp__share-tooltip" />
          </div>

          <div
            className={classnames(
              classes.navButtonContainer,
              classes.prevButtonContainer
            )}
            ref={prevButtonRef}
          >
            <button className="pswp__button--arrow--left photoswipe-navigate-button product-photo-slider-button product-photo-slider-button_left" />

            <ArrowIcon className="photoswipe-navigate-button-icon photo-swipe-icon" />
          </div>

          <div
            className={classnames(
              classes.navButtonContainer,
              classes.nextButtonContainer
            )}
            ref={nextButtonRef}
          >
            <button className="pswp__button--arrow--right photoswipe-navigate-button product-photo-slider-button product-photo-slider-button_right" />

            <ArrowIcon className="photoswipe-navigate-button-icon photoswipe-navigate-button-icon_reflected photo-swipe-icon" />
          </div>

          <div className="pswp__caption">
            <div className="pswp__caption__center" />
          </div>

          <div
            className="photoswipe-close-button"
            onClick={() => gallery.close()}
          >
            <TimesIcon className="photoswipe-close-button-icon photo-swipe-icon" />
          </div>
        </div>
      </div>
    </div>
  );
};

export default PhotoSwipeContainer;
