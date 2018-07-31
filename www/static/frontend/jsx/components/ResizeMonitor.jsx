import _ from 'lodash';
import ScreenSize from "../utils/ScreenSize";
import { actionMedia } from '../redusers/appHeadReduser';

class ResizeMonitor {

    constructor() {

        this.screen = new ScreenSize();

        this.onResize = _.throttle(this.onResize.bind(this), 200);
        this.screen.setCallback(this.onResize);

        this.state = this.screen.getInfo();
        actionMedia(this.state.media);
    }

    createNewState(state, info) {
        let newState;

        if (state.media != info.media) {
            newState = _.extend(state, info);
        }

        return newState;
    }

    onResize(info) {
        let newState = this.createNewState(this.state, info);
        if (newState) {
            this.fireEvent(newState);
            this.state = newState;
            actionMedia(this.state.media);
        }
    };

    fireEvent(state) {
        // component.sly.resize
        let event = new CustomEvent('resize_monitor.media_change', { 'detail': state });
        document.dispatchEvent(event);
    }

    destructor() {
        this.screen.destructor();
    }
}

var resizeMonitorExample;

export default function() {
    if(resizeMonitorExample) {
        return resizeMonitorExample;
    }

    resizeMonitorExample = new ResizeMonitor();
    return resizeMonitorExample;
}

