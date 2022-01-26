import React from "react";
import ImageComplex from "./ImageComplex";
import ImageNo from "./ImageNo";
import ImageSingle from "./ImageSingle";
import classnames from "classnames";

export default class Image extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const {
      images,
      mpn,
      upc,
      url,
      name,
      classes,
      isNew,
      isSale,
      inList,
      onFlagClick,
    } = this.props;
    const containerClasses = [classes.container];
    const linkClasses = [classes.link];
    const noImageClasses = [classes.noImage];

    return (
      <div className={classnames(containerClasses)}>
        <a href={url} title={name} className={classnames(linkClasses)}>
          {(() => {
            if (images.length === 0) {
              return <ImageNo upc={upc} mpn={mpn} classes={noImageClasses} />;
            } else if (images.length === 1) {
              return (
                <ImageSingle
                  upc={upc}
                  mpn={mpn}
                  image={images[0]}
                  inList={inList}
                  classes={classes}
                  onFlagClick={onFlagClick}
                />
              );
            } else {
              return (
                <ImageComplex
                  upc={upc}
                  mpn={mpn}
                  images={images}
                  classes={classes}
                />
              );
            }
          })()}

          {isNew && (
            <span className="splash image-splash image-splash__new show-for-large image_splash">
              New
            </span>
          )}

          {isSale && (
            <span className="splash image-splash image-splash__sale show-for-large image_splash">
              Sale
            </span>
          )}
        </a>
      </div>
    );
  }
}
