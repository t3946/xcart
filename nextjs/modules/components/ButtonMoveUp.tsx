import {h, Component, render} from 'preact';
import ScrollMonitor from './ScrollMonitor';


export default class ButtonMoveUp extends Component {
    constructor(props) {
        super(props);
        this.scroll = ScrollMonitor();
        this.state = {
            active: !this.scroll.scrolledTop()
        };

        document.addEventListener('components.scroll_monitor.scrolled_top', this.hideButton.bind(this));
        document.addEventListener('components.scroll_monitor.scrolled_from_top', this.showButton.bind(this));
    }

    hideButton(e) {
        let state = this.state;
        state.active = false;
        this.setState(state);
    }

    showButton(e) {
        let state = this.state;
        state.active = true;
        this.setState(state);
    }

    scrollUp() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }

    render(props, state) {
        let classString = 'button-move-up';
        let classStringContainer = 'button-move-up-container';
        if (props.className) {
            classStringContainer += ' ' + props.className;
        }
        if (!state.active) {
            classStringContainer += ' disabled';
        }
        return (<div className={classStringContainer}>
            <a className={classString} onClick={() => {
                this.scrollUp();
            }}>
                {props.label}
            </a>
        </div>);
    }

}