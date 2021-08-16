import { h, Component } from "preact";
import storeApp from "../redux/stores/StoreApp";
import renderToStringr from "preact-render-to-string";
import { videoLinkToObject } from "../utils/video";
import SwiperCore, { Navigation } from "swiper";
import PhotoSwipe from "./PhotoSwipeContainer";
import throttle from "lodash/throttle";
import extend from "lodash/extend";
import map from "lodash/map";
import { actionMedia } from "../redux/reduсers/appHeadReduсer";
import ScreenSize from "../utils/ScreenSize";
import React from "react";
import { Swiper, SwiperSlide } from "swiper/react";

React.useLayoutEffect = React.useEffect;
SwiperCore.use([Navigation]);

export default class ProductImageSlider extends Component {
  constructor(props) {
    super();

    let len = 0,
      wait = 0;

    if (props.items) {
      len = props.items.length;
    }

    this.preparedItems = null;
    this.refs = {};

    this.onResize = throttle(this.onResize.bind(this), 200);

    // добавить слушатель события resize
    document.addEventListener("resize_monitor.media_change", this.onResize);

    let globalState = storeApp.getState();
    let media = globalState.frontend.media;
    if (media == "") {
      let screen = new ScreenSize();
      state = screen.getInfo();
      actionMedia(state.media);
      media = state.media;
    }
    let state = {
      height: 400,
      loading: true,
      items: props.items || [],
      count: len,
      wait: len,
      isVideo: false,
      index: 0,
      media: media,
    };

    this.state = extend(state, this.createNewState(state, { media: media }));
    this.prepareItems(this.state.items);
  }

  componentWillUnmount() {
    document.removeEventListener("resize_monitor.media_change", this.onResize);
  }

  componentDidMount() {
    console.log(this.props);
  }

  createNewState(state, info) {
    let showThumbs, newState;

    if (info.media == "small" || info.media == "sm") {
      showThumbs = false;
    } else {
      showThumbs = true;
    }

    if (state.media != info.media || state.showThumbs != showThumbs) {
      newState = extend(state, {
        media: info.media,
        showThumbs: showThumbs,
      });
    }

    return newState;
  }

  onResize(event) {
    let newState = this.createNewState(this.state, event.detail);
    if (newState) {
      this.setState(newState);
    }
  }

  prepareItems(items) {
    for (let i in items) {
      let item = items[i];

      if (item.type === "image") {
        let wait = --this.state.wait;

        this.setState({
          wait: wait,
          loading: !!wait,
        });
      }

      if (item.type === "video") {
        videoLinkToObject(item.href, (meta) => {
          let wait = --this.state.wait;
          items[i] = extend(item, { meta: meta });

          this.setState({
            wait: wait,
            loading: !!wait,
          });
        });
      }
    }
  }

  clickHndl(e, n, item) {
    e.preventDefault();

    if (this.state.index !== n) {
      this.productImagesSlider.slideTo(n + 1);
      this.setState({
        index: n,
        isVideo: false,
      });
    }
  }

  zoomHndl(e, item) {
    e.preventDefault();

    if (!this.preparedItems) {
      let items = [];
      for (let i in this.state.items) {
        let item = this.state.items[i];

        if (item.type === "image") {
          items.push({ src: item.src, w: null, h: null });
        } else if (item.type === "html") {
          items.push({ html: item.html });
        } else if (item.type === "video") {
          items.push({
            originalItem: item,
            html: renderToStringr(
              <div className="slide-wrapper slider-detail">
                <div className="video-wrapper">
                  {this.renderVideoItem(item)}
                </div>
              </div>
            ),
            onTap: (item, pswp) => {
              if (!item.videoShow) {
                $(item.container).find(".video-wrapper")[0].innerHTML =
                  renderToStringr(
                    this.renderVideoItem(item.originalItem, true, true)
                  );
              }

              item.videoShow = true;
            },
            onBlur: (item, pswp) => {
              if (item.container && item.videoShow) {
                item.videoShow = false;
                $(item.container).find(".video-wrapper")[0].innerHTML =
                  renderToStringr(this.renderVideoItem(item.originalItem));
              }
            },
          });
        }
      }

      this.preparedItems = items;
    }

    let pswp = PhotoSwipe;
    pswp.options.index = this.state.index;
    pswp.options.speed = 300;
    pswp.options.zoomEl = false;
    pswp.options.maxSpreadZoom = 1;
    pswp.setImages(this.preparedItems);
    pswp.init();
  }

  prevHndl(e) {
    e.preventDefault();

    if (this.state.index) {
      this.productImagesSlider.slideTo(this.state.index - 1);
      this.setState({
        index: this.state.index - 1,
        isVideo: false,
      });
    }
  }

  nextHndl(e) {
    e.preventDefault();

    if (this.state.index < this.state.count - 1) {
      this.productImagesSlider.slideTo(this.state.index + 2);
      this.setState({
        index: this.state.index + 1,
        isVideo: false,
      });
    }
  }

  onSlideActive(eventName, index) {
    if (this.state.index !== index) {
      this.setState({
        index: index,
        isVideo: false,
      });
    }
  }

  renderThumbs() {
    return map(this.state.items, (item, n) => {
      let is_active = this.state.index == n ? " active" : "";
      //let is_active = '';

      if (item.type === "image") {
        return (
          <SwiperSlide
            className={"slide type-image" + is_active}
            key={"image.thumb." + n}
            onClick={(e) => {
              this.clickHndl(e, n, item);
            }}
            style={"background-image: url(" + item.thumb + ")"}
          />
        );
      }
      if (item.type === "video") {
        let src = item.thumb || item.meta.images.thumb || null;

        if (src) {
          return (
            <SwiperSlide
              className={"slide type-video play-icon" + is_active}
              key={"video.thumb." + n}
              onClick={(e) => {
                this.clickHndl(e, n, item);
              }}
              style={"background-image: url(" + src + ")"}
            />
          );
        } else {
          return (
            <SwiperSlide
              className={"slide type-video" + is_active}
              key={"video.thumb." + n}
              onClick={(e) => {
                this.clickHndl(e, n, item);
              }}
            >
              <span>No image</span>
            </SwiperSlide>
          );
        }
      }

      if (item.type === "html") {
        return (
          <SwiperSlide
            className={"slide type-html" + is_active}
            key={"html.thumb." + n}
            onClick={(e) => {
              this.clickHndl(e, n, item);
            }}
          >
            HTML
          </SwiperSlide>
        );
      }
    });
  }

  renderVideoItem(item, forceVideo = false, autolay = true) {
    if (item.meta.type === "youtube") {
      if (this.state.isVideo || forceVideo) {
        return (
          <iframe
            src={
              item.meta.p +
              "www.youtube.com/embed/" +
              item.meta.id +
              "?autoplay=" +
              (autolay ? 1 : 0)
            }
            type={"text/html"}
            frameborder="0"
            width={640}
            height={360}
            allowfullscreen
          />
        );
      } else {
        return this.renderImage(item.img || item.meta.images.img, "play-icon");
      }
    }
  }

  renderImage(src, classes = "") {
    return (
      <div
        className={"image " + classes}
        style={"background-image: url(" + src + ")"}
      />
    );
  }

  renderAllDetails() {
    return map(this.state.items, (item, n) => {
      let is_active = "";
      let position = n + 1;
      let key = "detail." + position;

      if (item.type === "image") {
        return (
          <SwiperSlide
            key={key}
            onClick={(e) => {
              this.zoomHndl(e, item);
            }}
            style={"background-image: url(" + item.preview + ")"}
          />
        );
      }

      if (item.type === "video") {
        let content = this.renderVideoItem(item);
        let clName = "slide type-video ";

        clName += this.state.isVideo ? "video-show" : "video-hide";

        return (
          <div
            className={clName}
            onClick={(e) => {
              this.zoomHndl(e, item);
            }}
            key={key}
          >
            {content}
          </div>
        );
      }
    });
  }

  renderSlyDetails() {
    if (this.state.count) {
      return (
        <SwiperSlide
          change={{
            function() {
              alert("change");
            },
          }}
        >
          <div
            className="frame"
            ref={(el) => (this.refs.frameDetail = el)}
            style={{ height: this.state.height }}
          >
            {this.renderAllDetails()}
          </div>
        </SwiperSlide>
      );
    }

    return null;
  }

  renderDetailClickBar() {
    if (this.state.showThumbs || this.state.count < 2) {
      return;
    }
    return map(this.state.items, (item, n) => {
      let index = n + 1;
      let key = "detailClick." + index;
      let classList = "clickBarItem";
      if (this.state.index == n) {
        classList += " active";
      }

      return (
        <li
          className={classList}
          key={key}
          onClick={(e) => {
            this.clickHndl(e, n, item);
          }}
        >
          {index}
        </li>
      );
    });
  }

  renderSliderThumbs() {
    let sliderButtonsClasses = this.state.items.length <= 3 ? " hide" : "";
    let buttonStyles = {
      width: "100%",
    };
    let hideEl = !this.state.showThumbs ? "display:none" : "";

    return (
      <div className="slider-thumbs" style={hideEl}>
        <button
          className={"prev product-thumbs-slider-prev" + sliderButtonsClasses}
          onClick={(e) => {
            this.prevHndl(e);
          }}
          ref={(el) => (this.refs.prev = el)}
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
          style={{
            marginBottom: 10,
          }}
          spaceBetween={5}
          longSwipesRatio={0.05}
          slidesPerView={"auto"}
          effect={"coverflow"}
          direction={"vertical"}
          className={"product-thumbs-slider swiper-container"}
          navigation={{
            nextEl: ".product-thumbs-slider-next",
            prevEl: ".product-thumbs-slider-prev",
          }}
          onSwiper={(swiper) => (this.productImagesThumbsSlider = swiper)}
        >
          {this.renderThumbs()}
        </Swiper>

        <button
          className={"next product-thumbs-slider-next" + sliderButtonsClasses}
          onClick={(e) => {
            this.nextHndl(e);
          }}
          ref={(el) => (this.refs.next = el)}
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
  }

  onChange() {
    if (!this.productImagesSlider) {
      return;
    }

    const index = this.productImagesSlider.realIndex;
    this.setState({
      index: index,
      isVideo: false,
    });
  }

  render() {
    console.log(this.state.loading);
    if (this.state.loading) {
      return <div className="slider loading" />;
    }

    const detail = this.renderDetailClickBar();

    return (
      <div className="images-slider">
        {this.renderSliderThumbs()}

        <Swiper
          spaceBetween={50}
          style={{
            marginBottom: 10,
          }}
          longSwipesRatio={0.05}
          slidesPerView={1}
          loop={true}
          effect={"coverflow"}
          className={"product-images-slider swiper-container"}
          onSwiper={(swiper) => (this.productImagesSlider = swiper)}
          onSlideChange={() => this.onChange()}
        >
          {this.renderAllDetails()}
        </Swiper>
      </div>
    );
  }
}
