import map from "lodash/map";
import { h, render, Component } from "preact";
import CustomSelectOptions from "./CustomSelectOptions";
import CustomColorOptions from "./CustomColorOptions";
import selectOption from "./SelectOption";

class CustomSelectField {
  constructor(button, win) {
    this.win = win;
    this.button = button;

    let selectId = this.button.dataset.select;

    this.select = document.getElementById(selectId);
    this.isColor = this.select.classList.contains("color");

    this.select.value = "";
    this.processButtonClick = this.processButtonClick.bind(this);
    this.button.addEventListener("click", this.processButtonClick, {
      passive: true,
    });
  }

  processButtonClick(event) {
    if (!this.win.classList.contains("opened")) {
      this.openOptions();
    }
  }

  openOptions() {
    let options = this.select.getElementsByTagName("option");
    let items = map(options, (el) => selectOption(this.button, el));
    let self = this;

    $(this.win).mmodal({
      windowClass: "selector-options",
      setWidth: false,
      onBeforeOpen: function (container) {
        if (self.isColor) {
          render(
            <CustomColorOptions
              items={items}
              callback={this.close.bind(this)}
            />,
            container,
            container.firstChild
          );
          return;
        }

        render(
          <CustomSelectOptions
            items={items}
            callback={this.close.bind(this)}
          />,
          container,
          container.firstChild
        );
      },
    });
  }

  destructor() {
    this.button.removeEventListener("click", this.processButtonClick, {
      passive: true,
    });
  }
}

export default (button, win) => {
  return new CustomSelectField(button, win);
};
