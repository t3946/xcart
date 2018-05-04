import {h, render, Component} from "preact";
import renderToStringr from 'preact-render-to-string';
import { videoLinkToObject } from "../utils/video";
import PhotoSwipe from "./PhotoSwipeContainer";
import _ from 'lodash';
import PreactSlySlide from "./PreactSlySlide";

export default class ProductImageSlider extends Component
{
    constructor( props ) {
        super();

        let len = 0, wait = 0;
        if (props.items) {
            len = props.items.length;
        }

        this.preparedItems = null;
        this.refs = {};

        this.onResize = _.throttle(this.onResize, 200);

        this.state = {
            height: 400,
            loading: true,
            items: props.items || [],
            count: len,
            wait: len,
            isVideo: false,
            index: 0,
        };

        this.prepareItems(this.state.items);
    }

    componentDidMount() {
        window.addEventListener("resize", this.onResize.bind(this));
        this.onResize()
    }
    componentWillUnmount() {
        window.removeEventListener("resize", this.onResize);
    }

    onResize() {
        if (this.refs.frame) {
            let height = this.refs.frame.getBoundingClientRect().height;

            if (this.state.height !== height) {
                this.setState({ height: height });
            }
        }
    };

    prepareItems(items)
    {

        for (let i in items) {
            let item = items[i];

            if (item.type === 'image') {
                let wait = --this.state.wait;

                this.setState({
                    wait: wait,
                    loading: (!!wait),
                });
            }

            if (item.type === 'video') {
                videoLinkToObject(item.href, (meta)=>{
                    let wait = --this.state.wait;
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
        e.preventDefault();

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
                            <div className="slide-wrapper slider-detail">
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

        if (this.state.index) {
            this.setState({
                index: this.state.index-1,
                isVideo: false,
            });
        }
    }

    nextHndl(e) {
        e.preventDefault();

        if (this.state.index < this.state.count-1) {
            this.setState({
                index: this.state.index+1,
                isVideo: false,
            });
        }
    }

    videoShowHndl(e) {
        e.preventDefault();

        this.setState({isVideo: true})
    }

    renderThumbs() {
        return _.map(this.state.items, (item, n)=>{

            // let is_active = (this.state.index == n ? ' active' : '');
            let is_active = '';

            if (item.type === 'image') {
                return (
                    <div className={"slide type-image" + is_active} key={"image.thumb." + n} onClick={e=>{this.clickHndl(e, n, item)}}
                         style={"background-image: url("+item.src+")"}
                    >
                    </div>
                );
            }
            if (item.type === 'video') {

                let src = item.thumb || item.meta.images.thumb || null;

                if (src) {
                    return (
                        <div className={"slide type-video play-icon" + is_active}
                             key={"video.thumb." + n}
                             onClick={ e => {this.clickHndl(e, n, item)}}
                             style={"background-image: url("+src+")"}
                        >
                        </div>
                    );
                }
                else {
                    return (
                        <div className={"slide type-video" + is_active} key={"video.thumb." + n} onClick={e=>{this.clickHndl(e, n, item)}}>
                            <span>No image</span>
                        </div>
                    );
                }
            }

            if (item.type === 'html') {
                return (
                    <div className={"slide type-html" + is_active} key={"html.thumb." + n} onClick={e=>{this.clickHndl(e, n, item)}}>
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
                return this.renderImage(item.img || item.meta.images.img, 'play-icon');
            }

        }
    }

    renderImage(src, classes = '')
    {
        return <div className={"image " + classes} style={"background-image: url("+src+")"}></div>
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
                        <div className="slide type-image" key={key} onClick={ e => {this.zoomHndl(e, item)}}>
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


                    return <div className={clName} onClick={ e => {this.zoomHndl(e, item)}} key={key}>{content}</div>;
                }
            }
        }

        return null;
    }

    render() {
        if (this.state.loading) {
            return <div className="slider loading"></div>;
        }

        let sliderButtonsClasses = (this.state.items.length <= 3) ? 'hide':'';
        let buttonStyles = {
            'width': '100%',
        };


        return (
        <div className="images-slider">
            <div className="slider-thumbs">
                <button className={"prev " + sliderButtonsClasses} onClick={ e => {this.prevHndl(e)}} ref={el => this.refs.prev = el } style={buttonStyles}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="31.75" height="17.688" viewBox="0 0 31.75 17.688">
                        <path class="prev_path"
                              d="M90.364,222.341l-0.728-.685,16-17,0.728,0.686Zm30.272,0,0.728-.685-16-17-0.728.686Z"
                              transform="translate(-89.625 -204.656)"/>
                    </svg>
                </button>
                <PreactSlySlide
                    pos={this.state.index}
                    options={{
                        horizontal: 0,
                        speed: 300,
                        mouseDragging: 1,
                        touchDragging: 1,
                        smart: 1,
                        prev: this.refs.prev,
                        next: this.refs.next,
                    }}>
                    <div className="frame" ref={ el => this.refs.frame = el }  style={{'height': this.state.height}}>
                        {this.renderThumbs()}
                    </div>
                </PreactSlySlide>
                <button className={"next " + sliderButtonsClasses} onClick={ e =>{this.nextHndl(e)}} ref={el => this.refs.next = el } style={buttonStyles}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="31.75" height="17.688" viewBox="0 0 31.75 17.688">
                        <path class="next_path"
                              d="M120.636,279.657l0.728,0.685-16,17-0.728-.685Zm-30.272,0-0.728.685,16,17,0.728-.685Z"
                              transform="translate(-89.625 -279.656)"/>
                    </svg>
                </button>
            </div>
            <div className="slider-detail">
                <div className="wrap">
                    {this.renderDetail()}
                </div>
            </div>
        </div>);
    }
}