import React from "react";
import classnames from "classnames";

export default class ImageNo extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { mpn, upc, classes } = this.props;

    classes.push("product-card-no-image");

    return (
      <div className={classnames(classes)}>
        <span>Image not available</span>
        <meta itemProp="mpn" content={mpn} />
        {upc && <meta itemProp="gtin" content={upc} />}
      </div>
    );
  }
}
