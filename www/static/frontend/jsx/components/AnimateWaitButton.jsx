const DURATION_PRELOAD = 500;
const DURATION_SHOW_INFO = 500;

class AnimateWaitButton {
  constructor(identifier) {
    identifier = identifier || ".wait-button";
    let element;

    if (typeof element == "string") {
      this._element = document.querySelector(identifier);
    } else {
      this._element = identifier;
    }
  }

  start() {
    if (typeof this._element === "string") {
      return;
    }

    this._element.classList.add("wait", "loading");
    this._element.addEventListener("click", this.blockAllEvents);

    setTimeout(() => {
      this._element.classList.remove("loading");

      setTimeout(() => {
        this._element.classList.remove("wait");
        this._element.removeEventListener("click", this.blockAllEvents);
      }, DURATION_SHOW_INFO);
    }, DURATION_PRELOAD);
  }

  blockAllEvents(event) {
    event.stopPropagation();
    event.preventDefault();
    return false;
  }
}

export default function CreateWaitButton(identifier) {
  return new AnimateWaitButton(identifier);
}
