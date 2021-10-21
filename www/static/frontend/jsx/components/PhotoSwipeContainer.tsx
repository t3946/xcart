import PhotoSwipe from "@client/libs/photoswipe/dist/photoswipe";
import PhotoSwipeUI_Default from "@client/libs/photoswipe/dist/photoswipe-ui-default";
import React from "react";
import { useSelector, useDispatch } from "react-redux";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import {
  photoSwipeInitAction,
  photoSwipeClearAction,
} from "@client/jsx/redux/actions/PhotoswipeActions";
import classnames from "classnames";

const PhotoSwipeContainer: React.FC = function () {
  const photoSwipeStore = useSelector((e: StoreInterface) => e.photoswipe);
  const items = photoSwipeStore.items;

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

  const options = {
    index: 0,
    speed: 300,
    bgOpacity: 0.91,
    zoomEl: false,
    maxSpreadZoom: 1,
    showHideOpacity: true,
  };

  const classes: Record<any, any> = {
    navButtonContainer: ["product-photo-slider-button-container"],
    prevButtonContainer: ["photoswipe-left-arrow"],
    nextButtonContainer: ["photoswipe-right-arrow"],
    prevButtonIcon: ["photoswipe-left-arrow"],
    nextButtonIcon: ["photoswipe-right-arrow"],
  };

  if (gallery) {
  }

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

  /**
   * change prev nex buttons offsets
   */
  function changePadding(gallery): void {
    const container = gallery.currItem.container;

    if (!container) {
      return;
    }

    const image = container.lastChild;
    const imageWidth = image.offsetWidth;
    const zoomScale = parseFloat(
      container.style.transform.match(/scale\((.*?)\)/)[1]
    );
    const visibleWidth = imageWidth * zoomScale;
    const offset = Math.ceil(visibleWidth / 2) + 50;

    prevButtonRef.current.style.paddingRight = `${offset}px`;
    nextButtonRef.current.style.paddingLeft = `${offset}px`;
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
    });

    // close gallery handler
    gallery.listen("close", () => {
      document.body.style.overflow = "initial";

      const item = gallery.currItem;

      if (item.onBlur) {
        item.onBlur(item, gallery);
      }

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
      }
    });

    gallery.listen("afterChange", () => {
      const item = gallery.currItem;

      if (item.onShow) {
        item.onShow(item, gallery);
      }

      changePadding(gallery);
      setForceUpdate({ ...forceUpdate });
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

    dispatch(photoSwipeInitAction(gallery));
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

            <img
              className="photoswipe-navigate-button-icon"
              src="/static/frontend/dist/images/photoswipe/arrow.svg"
              alt=""
            />
          </div>

          <div
            className={classnames(
              classes.navButtonContainer,
              classes.nextButtonContainer
            )}
            ref={nextButtonRef}
          >
            <button className="pswp__button--arrow--right photoswipe-navigate-button product-photo-slider-button product-photo-slider-button_right" />

            <img
              className="photoswipe-navigate-button-icon"
              src="/static/frontend/dist/images/photoswipe/arrow.svg"
              style={{ transform: "rotateY(180deg)" }}
              alt=""
            />
          </div>

          <div className="pswp__caption">
            <div className="pswp__caption__center" />
          </div>

          <div
            className="photoswipe-close-button"
            onClick={() => gallery.close()}
          >
            <img
              className="photoswipe-close-button-icon"
              src="/static/frontend/dist/images/photoswipe/cross.svg"
              alt=""
            />
          </div>
        </div>
      </div>
    </div>
  );
};

export default PhotoSwipeContainer;
