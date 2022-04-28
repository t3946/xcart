import * as React from "react";
import getStoreUrl from "@utils/getStoreUrl";
import ImageNotAvailable from "@components/common/image-not-available/ImageNotAvailable";
import cn from "classnames";

interface IProps {
  src?: string;
  width?: any;
  height?: any;
  classes?: {
    image?: any;
    imageNo?: any;
  };
}

export const Image: React.FC<IProps> = function (props) {
  const { src, width, height, classes } = props;

  if (!src) {
    return <ImageNotAvailable className={cn(classes?.image)} />;
  }

  return (
    <img
      src={getStoreUrl(src)}
      alt={"a"}
      width={width}
      height={height}
      className={cn(classes?.imageNo)}
    />
  );
};

export default Image;
