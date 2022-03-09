import { h, render, Component } from "preact";

class SelectOption {
  constructor(button, element) {
    this.element = element;
    this.value = element.value;
    this.text = element.text;
    this.button = button;
  }

  setActive(element) {
    element = element || null;
    $(this.element).siblings().removeAttr("selected");
    this.element.setAttribute("selected", "selected");
    let select = this.element.closest("select");
    select.value = this.value;

    if (element == null) {
      this.button.innerHTML = this.text;
      return;
    }

    this.button.innerHTML = "";
    render(element, this.button, this.button.firstChild);
  }

  isActive() {
    return this.element.hasAttribute("selected");
  }

  isDisabled() {
    return (
      this.element.hasAttribute("disabled") ||
      this.element.hasAttribute("hidden")
    );
  }
}

export default (button, element) => {
  return new SelectOption(button, element);
};
