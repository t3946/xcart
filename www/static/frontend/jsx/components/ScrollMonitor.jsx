const DELTA_EDGE = 20;

class ScrollMonitor {
  constructor() {
    this._lastKnownScrollPosition = 0;
    this._ticking = false;
    this._top = this._scrolledTop(window.scrollY);
    this._bottom = this._scrolledBottom(window.scrollY);
    window.addEventListener("scroll", this._onScroll.bind(this));
  }

  scrolledBottom() {
    return this._bottom;
  }

  scrolledTop() {
    return this._top;
  }

  destructor() {
    window.removeEventListener("scroll", this._onScroll.bind(this));
  }

  _onScroll(event) {
    this._lastKnownScrollPosition = window.scrollY;
    if (!this._ticking) {
      window.requestAnimationFrame(() => {
        this._processScrollChange(this._lastKnownScrollPosition);
        this._ticking = false;
      });
      this._ticking = true;
    }
  }

  _processScrollChange(position) {
    if (this._scrolledTop(position)) {
      this._processScrolledTop();
    } else {
      this._processNotTop();

      if (this._scrolledBottom(position)) {
        this._processScrolledBottom();
      } else {
        this._processNotBottom();
      }
    }
  }

  _processNotTop() {
    if (this._top) {
      document.dispatchEvent(
        new Event("components.scroll_monitor.scrolled_from_top")
      );
      this._top = false;
    }
  }

  _processNotBottom() {
    if (this._bottom) {
      document.dispatchEvent(
        new Event("components.scroll_monitor.scrolled_from_bottom")
      );
      this._bottom = false;
    }
  }

  _scrolledTop(position) {
    return position <= DELTA_EDGE;
  }

  _scrolledBottom(position) {
    return (
      document.body.scrollHeight - position - document.body.clientHeight <=
      DELTA_EDGE
    );
  }

  _processScrolledTop() {
    if (!this._top) {
      document.dispatchEvent(
        new Event("components.scroll_monitor.scrolled_top")
      );
      this._top = true;
    }
  }

  _processScrolledBottom() {
    if (!this._bottom) {
      document.dispatchEvent(
        new Event("components.scroll_monitor.scrolled_bottom")
      );
      this._bottom = true;
    }
  }
}

var monitor;

export default () => {
  if (!monitor) {
    monitor = new ScrollMonitor();
  }
  return monitor;
};
