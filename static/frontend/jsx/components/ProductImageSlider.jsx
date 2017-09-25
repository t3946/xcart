import PhotoSwipe from "PhotoSwipe";
import {h, Component} from "preact";
import { videoLinkToObject } from "../utils/video";
import _ from 'lodash';

export default class ProductImageSlider extends Component
{
    constructor( props ) {
        super();

        // let objects = [];
        // if (props.images) {
        //     objects = _.concat(objects, props.images)
        // }
        // if (props.videos) {
        //     objects = _.concat(objects, props.videos)
        // }

        let len = 0, wait = 0;
        if (props.items) {
            len = props.items.length;
        }

        this.ps = new PhotoSwipe();
        // this.ps.init();

        this.state = {
            loading: true,
            items: props.items || [],
            count: len,
            wait: wait,
            index: 0,
        };

        this.prepareItems(this.state.items);
    }

    prepareItems(items)
    {
        let wait = this.state.wait;

        console.log(this.ps);

        for (let i in items) {
            let item = items[i];

            if (item.type === 'image') {

                // this.ps.items.push({
                //     src: item.src,
                // });
            }

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

        this.setState({index: n});
    }

    zoomHndl(e, item) {
        e.preventDefault();

        // this.ps.goTo(this.state.index);
    }

    prevHndl(e) {
        e.preventDefault();

    }

    nextHndl(e) {
        e.preventDefault();

    }

    renderImage(item)
    {
        if (item.src) {
            return <img data-src={item.src}  alt={item.name} className="lazy lazy-img" itemprop="image" />;
        }

        return (
            <div className="not-avail">
                <span className="text">
                    Image not available
                </span>
            </div>
        );
    }

    renderThumbs() {
        return _.map(this.state.items, (item, n)=>{

            let is_active = (this.state.index == n ? ' active' : '');

            if (item.type === 'image') {
                return (
                    <div className={"slide type-image" + is_active} key={"image.thumb." + n} onClick={(e)=>{this.clickHndl(e, n, item)}}>
                        <img data-src={item.src} className="lazy lazy-img"/>
                    </div>
                );
            }
            if (item.type === 'video') {

                let src = item.thumb || item.meta.images.thumb || null;

                if (src) {
                    return (
                        <div className={"slide type-video" + is_active} key={"video.thumb." + n} onClick={(e)=>{this.clickHndl(e, n, item)}}>
                            <img data-src={src} alt="" className="lazy lazy-img"/>
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
                            <img src={item.src} alt={item.alt} title={item.title} className="" itemprop="image"/>
                        </div>
                    );
                }

                if (item.type === 'html') {
                    return <div className="slide type-html" dangerouslySetInnerHTML={{__html:item.html}} key={key} ></div>;
                }

                if (item.type === 'video') {
                    let content = null;

                    if (item.meta.type === 'youtube') {
                        content = <iframe src={item.meta.p + "www.youtube.com/embed/" + item.meta.id} type={"text/html"} frameborder="0" width={640} height={360}></iframe>;
                    }

                    return <div className="slide type-video">{content}</div>;
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