import React from "react";
import classnames from "classnames";

/**
 * images viewer for product in products slider where many images
 */
export default class ImageComplex extends React.Component {
  constructor(props) {
    super(props);
    //view image index
    this.state = { activeIndex: 0 };
    this.hoverIntentTimeout = null;
  }

  render(props) {
    const { activeIndex } = this.state;
    const classes = {
      container: [
        "product-card-image-group",
        "images-many",
        `images-${props.images.length}`,
        props.classes.container,
      ],
    };

    return (
      <div className={classnames(classes.container)}>
        {props.images.map((image, i) => (
          <div
            key={`image-${i}`}
            className={
              "products-slider-images-group " +
              (i === activeIndex ? "show" : "hide")
            }
          >
            {image}
          </div>
        ))}
        <ul className="products-slider-images-navigator" style="display: flex">
          {props.images.map((image, i) => (
            <li
              onMouseOver={(e) => {
                clearTimeout(this.hoverIntentTimeout);
                this.hoverIntentTimeout = setTimeout(
                  () => this.setState({ activeIndex: i }),
                  35
                );
              }}
              onMouseOut={(e) => {
                clearTimeout(this.hoverIntentTimeout);
              }}
              className={classnames({
                "products-slider-images-nav-item": true,
                "products-slider-images-nav-item__active": i === activeIndex,
              })}
              key={`nav-item-${i}`}
            />
          ))}
        </ul>
      </div>
    );
  }
}
