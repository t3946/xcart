import { h, Component, render } from "preact";
import CustomSelectOptions from "./CustomSelectOptions";

export default class CustomColorOptions extends CustomSelectOptions {
  renderTextWithIcon(item) {
    //console.log(item.value);
    let valueParts = item.value.split("_");
    //console.log(valueParts);
    let style = "background-color:" + valueParts[1] + ";";
    return (
      <span className="selector-button__label__color">
        <span className="selector-button__label__color__icon" style={style} />
        <span className="selector-button__label__color__text">{item.text}</span>
      </span>
    );
  }

  changeActive(item) {
    let content = this.renderTextWithIcon(item);
    item.setActive(content);
    this.setState({
      changed: true,
    });
  }

  renderOneItem(item) {
    if (!item.isDisabled()) {
      let id = "select" + item.value;
      let checked = item.isActive();
      let content = this.renderTextWithIcon(item);

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
            className="hover-blue color selector-button__label"
            for={id}
            data-value={item.value}
            onClick={() => {
              this.changeActive(item);
            }}
          >
            {content}
          </label>
        </div>
      );
    } else {
      this.title = item.text;
    }
  }
}
