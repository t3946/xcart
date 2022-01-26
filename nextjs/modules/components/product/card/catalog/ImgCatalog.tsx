import React from "react";

export default class Image extends React.Component {
  constructor({ image }) {
    super();
    this.image = image;
    this.imageRef = React.createRef();
  }

  componentDidMount() {
    const imageElement = this.imageRef.current;
    const options = {
      root: null,
      rootMargin: "0px",
    };

    imageElement.setAttribute("style", "opacity: 0");

    imageElement.onload = function () {
      imageElement.removeAttribute("style");
    };

    imageElement.onerror = function () {
      imageElement.removeAttribute("style");
    };

    new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          observer.unobserve(imageElement);
          imageElement.setAttribute("src", this.image.url);
        }
      });
    }, options).observe(imageElement);
  }

  render() {
    return (
      <img
        alt={this.image.alt}
        className="products-slider-image"
        ref={this.imageRef}
      />
    );
  }
}
