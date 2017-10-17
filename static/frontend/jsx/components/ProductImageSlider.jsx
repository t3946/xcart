import {h, render, Component} from "preact";
import renderToStringr from 'preact-render-to-string';
import { videoLinkToObject } from "../utils/video";
import PhotoSwipe from "./PhotoSwipeContainer";
import _ from 'lodash';
import Swiper from 'react-id-swiper';

export default class ProductImageSlider extends Component
{
    constructor( props ) {
        super();

        let len = 0, wait = 0;
        if (props.items) {
            len = props.items.length;
        }

        this.preparedItems = null;

        this.onResize = _.throttle(this.onResize, 200);

        this.state = {
            height: 400,
            loading: true,
            items: props.items || [],
            count: len,
            wait: wait,
            isVideo: false,
            index: 0,
        };

        this.prepareItems(this.state.items);
    }

    componentDidMount() {
        window.addEventListener("resize", this.onResize.bind(this));
    }
    componentWillUnmount() {
        window.removeEventListener("resize", this.onResize);
    }

    onResize() {

        if (this._box) {
            let height = this._box.getBoundingClientRect().height;

            if (this.state.height !== height) {
                this.setState({ height: height });
            }
        }
    };

    prepareItems(items)
    {
        let wait = this.state.wait;

        for (let i in items) {
            let item = items[i];

            // if (item.type === 'image') { }

            if (item.type === 'video') {
                wait += 1;
                videoLinkToObject(item.href, (meta)=>{
                    let wait = this.state.wait--;
                    items[i] = _.extend(item, {meta: meta});

                    this.setState({
                        wait: wait,
                        loading: (!!wait),
                    });
                });
            }
        }
    }


    clickHndl(e, n, item) {
        // e.preventDefault();

        if (this.state.index !== n) {
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

                if (item.type === 'image') {
                    items.push({src: item.src, w: null, h: null});
                }
                else if (item.type === 'html') {
                    items.push({html: item.html});
                }
                else if (item.type === 'video') {
                    items.push({
                        originalItem: item,
                        html: renderToStringr(
                            <div className="slide-wrapper">
                                <div className="video-wrapper">
                                    {this.renderVideoItem(item)}
                                </div>
                            </div>),
                        onTap: (item, pswp) => {
                            if (!item.videoShow)
                            {
                                $(item.container)
                                    .find('.video-wrapper')[0]
                                    .innerHTML = renderToStringr(this.renderVideoItem(item.originalItem, true, true));
                            }

                            item.videoShow = true;

                        },
                        onBlur: (item, pswp) => {
                            if (item.container && item.videoShow) {
                                item.videoShow = false;
                                $(item.container)
                                    .find('.video-wrapper')[0]
                                    .innerHTML = renderToStringr(this.renderVideoItem(item.originalItem));
                            }
                        }
                    });
                }
            }

            this.preparedItems = items;
        }

        let pswp = PhotoSwipe;
        pswp.options.index = this.state.index;
        pswp.setImages(this.preparedItems);
        pswp.init();
    }

    prevHndl(e) {
        e.preventDefault();

    }

    nextHndl(e) {
        e.preventDefault();

    }

    videoShowHndl(e) {
        e.preventDefault();

        this.setState({isVideo: true})
    }

    renderThumbs() {
        return _.map(this.state.items, (item, n)=>{

            let is_active = (this.state.index == n ? ' active' : '');

            if (item.type === 'image') {
                return (
                    <div className={"slide type-image" + is_active} key={"image.thumb." + n} onClick={(e)=>{this.clickHndl(e, n, item)}}
                         style={"background-image: url("+item.src+")"}>
                    </div>
                );
            }
            if (item.type === 'video') {

                let src = item.thumb || item.meta.images.thumb || null;

                if (src) {
                    return (
                        <div className={"slide type-video" + is_active}
                             key={"video.thumb." + n}
                             onClick={(e)=>{this.clickHndl(e, n, item)}}
                             style={"background-image: url("+src+")"}
                        >
                        </div>
                    );
                }
                else {
                    return (
                        <div className={"slide type-video" + is_active} key={"video.thumb." + n} onClick={(e)=>{this.clickHndl(e, n, item)}}>
                            <span>No image</span>
                        </div>
                    );
                }
            }

            if (item.type === 'html') {
                return (
                    <div className={"slide type-html" + is_active} key={"html.thumb." + n} onClick={(e)=>{this.clickHndl(e, n, item)}}>
                        HTML
                    </div>
                );
            }
        });
    }

    renderVideoItem(item, forceVideo = false, autolay = true)
    {
        if (item.meta.type === 'youtube') {

            if (this.state.isVideo || forceVideo) {
                return (
                    <iframe
                        src={item.meta.p + "www.youtube.com/embed/" + item.meta.id + '?autoplay=' + (autolay ? 1:0)}
                        type={"text/html"}
                        frameborder="0"
                        width={640}
                        height={360}
                        allowfullscreen></iframe>
                );
            }
            else {
                let image = item.img || item.meta.images.img;
                return this.renderImage(image);
            }

        }
    }

    renderImage(src)
    {
        return <div className="image" style={"background-image: url("+src+")"}></div>
    }

    renderDetail()
    {
        if (this.state.count) {
            let position = this.state.index;

            if (position <= this.state.items.length) {
                let item = this.state.items[position];
                let key = 'detail.' + position;

                if (item.type === 'image') {
                    return (
                        <div className="slide type-image" key={key} onClick={(e)=>{this.zoomHndl(e, item)}}>
                            {/*<img src={item.src} alt={item.alt} title={item.title} className="" itemprop="image"/>*/}
                            {this.renderImage(item.src)}
                        </div>
                    );
                }

                if (item.type === 'html') {
                    return <div className="slide type-html" dangerouslySetInnerHTML={{__html:item.html}} key={key} ></div>;
                }

                if (item.type === 'video') {
                    let content = this.renderVideoItem(item);
                    let clName = "slide type-video ";

                    clName += this.state.isVideo? "video-show" : "video-hide";


                    return <div className={clName} onClick={(e)=>{ this.videoShowHndl(e);}} key={key}>{content}</div>;
                }
            }
        }

        return null;
    }

    render() {
        if (this.state.loading) {
            return <div className="slider loading"></div>;
        }

        return (
        <div className="images-slider">
            <div className="slider-thumbs">
                <a href="#" className="prev" onClick={(e)=>{this.prevHndl(e)}}></a>
                <div className="wrap" ref={(el) => this._box = el }>
                    <Swiper {...{
                        direction: 'vertical',
                        slidesPerView: 'auto',
                        // slidesPerView: 5,
                        mousewheelControl: true,
                        paginationClickable: false,
                        freeMode: true,
                        height: this.state.height,
                        freeModeFluid:  true,
                        freeModeSticky: false,
                        followFinger:   true,
                        // autoHeight: true,
                    }}>
                        {this.renderThumbs()}
                    </Swiper>
                </div>
                <a href="#" className="next" onClick={(e)=>{this.nextHndl(e)}}></a>
            </div>
            <div className="slider-detail">
                <div className="wrap">
                    {this.renderDetail()}
                </div>
            </div>
        </div>);
    }
}