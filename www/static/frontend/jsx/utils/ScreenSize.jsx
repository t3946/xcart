import _ from "lodash";
import getMediaJs from "./getMediaJs";

export default class ScreenSize {

    constructor() {
        this.onResize = _.throttle(this.onResize.bind(this), 200);
        this.width = window.innerWidth;
        this.height = window.innerHeight;
        this.media = getMediaJs(this.width);
        window.addEventListener('resize', this.onResize, {passive: true});
    }

    setCallback(callback) {
        this.callback = callback;
    }

    getInfo() {
        return {
            'width': this.width,
            'height': this.height,
            'media': this.media
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
        this.media = getMediaJs(this.width);
        this.executeCallback();
    };

    destructor() {
        window.removeEventListener('resize', this.onResize);
    }
}