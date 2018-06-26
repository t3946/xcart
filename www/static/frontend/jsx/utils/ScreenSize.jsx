import _ from "lodash";
import getMediaJs from "./getMediaJs";

export default class ScreenSize {

    constructor() {
        this.onResize = _.throttle(this.onResize.bind(this), 200);
        this.width = window.innerWidth;
        this.height = window.innerHeight;
        this.ratio = window.devicePixelRatio;
        this.cssWidth = Math.round(this.width / this.ratio);
        this.media = getMediaJs(this.cssWidth);
        window.addEventListener('resize', this.onResize, {passive: true});
    }

    setCallback(callback) {
        this.callback = callback;
    }

    getInfo() {
        return {
            'width': this.cssWidth,
            'media': this.media,
        };
    }

    executeCallback() {
        if (this.callback) {
            this.callback(this.getInfo());
        }
    }

    onResize() {
        this.width = window.innerWidth;
        this.height = window.innerHeight;
        this.ratio = window.devicePixelRatio;
        this.cssWidth = Math.round(this.width / this.ratio);
        this.media = getMediaJs(this.cssWidth);
        this.executeCallback();
    };

    destructor() {
        window.removeEventListener('resize', this.onResize);
    }
}