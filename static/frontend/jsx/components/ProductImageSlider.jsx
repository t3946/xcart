import {h, render, Component} from "preact";
import renderToStringr from 'preact-render-to-string';
import { videoLinkToObject } from "../utils/video";
import PhotoSwipe from "./PhotoSwipeContainer";
import _ from 'lodash';

export default class ProductImageSlider extends Component
{
    constructor( props ) {
        super();

        let len = 0, wait = 0;
        if (props.items) {
            len = props.items.length;
        }

        this.preparedItems = null;

        this.state = {
            loading: true,
            items: props.items || [],
            count: len,
            wait: wait,
            isVideo: false,
            index: 0,
        };

        this.prepareItems(this.state.items);
    }

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
                        html: renderToStringr(
                            <div className="slide-wrapper">
                                <div className="video-wrapper" onClick={"this.innerHTML = '"+ renderToStringr(this.renderVideoItem(item, true, true))+"'"} >
                                    {this.renderVideoItem(item)}
                                </div>
                            </div>),
                        onTap: (e) => {
                            alert('hello');
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
                <div className="wrap">
                    {this.renderThumbs()}
                </div>
            </div>
            <div className="slider-detail">
                <div className="wrap">
                    {this.renderDetail()}
                </div>
            </div>
        </div>);
    }
}