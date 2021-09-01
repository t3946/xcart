import { h, Component, render } from "preact";
import extend from "lodash/extend";
import throttle from "lodash/throttle";

export default class PreactSlySlide extends Component {
  constructor(...args) {
    super(...args);

    this.refs = {};
    this["$refs"] = {};

    this.options = extend(
      {
        horizontal: 1,
        itemNav: "basic",
        speed: 300,
        mouseDragging: 1,
        touchDragging: 1,
        activatePageOn: "click",
        onSlideActive: null,
      },
      args[0].options || {}
    );
  }

  componentDidMount() {
    this.$refs.wrap = $(this.refs.wrap);
    this.$refs.wrap.sly(this.options);
    if (this.options.onSlideActive) {
      this.$refs.wrap.sly(
        "on",
        "active",
        throttle(this.options.onSlideActive, 200)
      );
    }

    document.addEventListener(
      "resize_monitor.media_change",
      throttle(this.onResize.bind(this))
    );
  }

  componentWillReceiveProps(props, prev) {
    if (this.$refs.wrap) {
      this.$refs.wrap.sly("activate", props.pos);
    }
  }

  componentWillUnmount() {
    document.removeEventListener("resize_monitor.media_change", this.onResize);
  }

  onResize() {
    this.$refs.wrap.sly("reload");
  }

  render({ children }) {
    return (
      <div className={"wrap"} ref={(el) => (this.refs.wrap = el)}>
        {children}
      </div>
    );
  }
}
