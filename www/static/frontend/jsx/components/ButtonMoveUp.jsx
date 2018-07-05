import {h, Component, render} from 'preact';
import _ from 'lodash';
import ScrollMonitor from './ScrollMonitor';



export default class ButtonMoveUp extends Component {
    constructor(props) {
        super(props);
        this.scroll = ScrollMonitor();
        this.state = {
            active: !this.scroll.scrolledTop()
        };

        document.addEventListener('components.scroll_monitor.scrolled_top', this.changeState.bind(this))
        document.addEventListener('components.scroll_monitor.scrolled_from_top', this.changeState.bind(this))
    }

    changeState(){
        console.log('MoveUp', 'active', !this.scroll.scrolledTop());
        let state = this.state;
        state.active = !this.scroll.scrolledTop()
        this.setState(state);
    }

    scrollUp(){
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }

    render(props, state) {
        let classString = 'button-move-up';
            if(!state.active){
                classString += ' disabled';
            }
        return (<div className="button-move-up-container">
            <a className={classString} onClick={() => { this.scrollUp(); }} >
                UP
            </a>
        </div>);
    }

}