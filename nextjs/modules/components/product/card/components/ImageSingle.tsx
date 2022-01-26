import React from "react";
import classnames from "classnames";

export default class ImageSingle extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const props = this.props;
    const containerClasses = ["images-1", props.classes.container];

    return (
      <div className={classnames(containerClasses)}>
        {props.image}
        <meta itemProp="mpn" content={props.mpn} />

        {props.upc && <meta itemProp="gtin" content={props.upc} />}
      </div>
    );
  }
}
