import {h, Component, render} from 'preact';
import _ from 'lodash';
import ButtonMoveUp from "./ButtonMoveUp";
import ScrollMonitor from "./ScrollMonitor";

export default class ButtonMoveDown extends Component {
    constructor(props) {
        super(props);
        this.scroll = ScrollMonitor();
        this.state = {
            active: !this.scroll.scrolledBottom()
        };

        document.addEventListener('components.scroll_monitor.scrolled_bottom', this.changeState.bind(this))
        document.addEventListener('components.scroll_monitor.scrolled_from_bottom', this.changeState.bind(this))
    }

    changeState(){
        console.log('MoveDown', 'active', !this.scroll.scrolledBottom());
        let state = this.state;
        state.active = !this.scroll.scrolledBottom()
        this.setState(state);
    }

    scrollDown(){
        window.scrollTo({
            top: document.body.scrollHeight - document.body.clientHeight,
            behavior: "smooth"
        });
    }

    render(props, state) {
        let classString = 'button-move-down';
        if(!state.active){
            classString += ' disabled';
        }
        return (<div className="button-move-down-container">
            <a className={classString} onClick={() => { this.scrollDown(); }} >
                DOWN
            </a>
        </div>);
    }

}