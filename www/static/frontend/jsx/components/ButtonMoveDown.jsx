import { h, Component, render } from "preact";
import ScrollMonitor from "./ScrollMonitor";

export default class ButtonMoveDown extends Component {
  constructor(props) {
    super(props);
    this.scroll = ScrollMonitor();
    this.state = {
      active: !this.scroll.scrolledBottom(),
    };

    document.addEventListener(
      "components.scroll_monitor.scrolled_bottom",
      this.hideButton.bind(this)
    );
    document.addEventListener(
      "components.scroll_monitor.scrolled_from_bottom",
      this.showButton.bind(this)
    );
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

  scrollDown() {
    window.scrollTo({
      top: document.body.scrollHeight - document.body.clientHeight,
      behavior: "smooth",
    });
  }

  render(props, state) {
    let classString = "button-move-down";
    let classStringContainer = "button-move-down-container";
    if (props.className) {
      classStringContainer += " " + props.className;
    }
    if (!state.active) {
      classStringContainer += " disabled";
    }
    return (
      <div className={classStringContainer}>
        <a
          className={classString}
          onClick={() => {
            this.scrollDown();
          }}
        >
          {props.label}
        </a>
      </div>
    );
  }
}
