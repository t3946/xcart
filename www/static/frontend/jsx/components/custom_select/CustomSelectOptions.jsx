import { h, Component, render } from "preact";
import map from "lodash/map";

export default class CustomSelectOptions extends Component {
  constructor(props) {
    super(props);
    if (props.items === null) {
      return;
    }
    this.close = props.callback;

    this.state = {
      changed: false,
    };
  }

  changeActive(item) {
    item.setActive();
    this.setState({
      changed: true,
    });
  }

  renderOneItem(item) {
    if (!item.isDisabled()) {
      let id = "select" + item.value;
      let checked = item.isActive();
      return (
        <div className="selector-button">
          <input
            id={id}
            className="selector-button__radio"
            name="custom_select_options"
            value={item.value}
            type="radio"
            checked={checked}
          />
          <label
            className="hover-blue selector-button__label"
            for={id}
            data-value={item.value}
            onClick={() => {
              this.changeActive(item);
            }}
          >
            {item.text}
          </label>
        </div>
      );
    } else {
      this.title = item.text;
    }
  }

  componentDidUpdate() {
    if (this.state.changed) {
      setTimeout(() => {
        this.close();
      }, 300);
    }
  }

  render(props, state) {
    let self = this;
    let options = map(props.items, this.renderOneItem.bind(self));
    return (
      <div>
        <div className="title">{this.title}</div>
        <div className="selector-options-items">{options}</div>
      </div>
    );
  }
}
