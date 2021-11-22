import React from "react";
import classnames from "classnames";
import ImageComplex from "./ImageComplex";
import ImageNo from "./ImageNo";
import ImageSingle from "./ImageSingle";
interface ImageCard {
  images: any;
  mpn: any;
  upc: string;
  url: string;
  name: string;
  classes: any;
  isNew: boolean;
  isSale: boolean;
}
export const ImageCard: React.FC<ImageCard> = ({
  images,
  name,
  mpn,
  isNew,
  isSale,
  upc,
  url,
  classes,
}) => {
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
                classes={classes}
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

        {isNew && !isSale (
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
};
