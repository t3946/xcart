import * as React from "react";
import Styles from "@components/common/image-not-available/ImageNotAvailable.module.scss";
import cn from "classnames";

interface IProps {
  className?: any;
}

export const ImageNotAvailable: React.FC<IProps> = function (props) {
  const { className } = props;

  return (
    <div
      className={cn([
        Styles.ImageNotAvailable,
        "w-100",
        "h-100",
        "d-flex",
        "align-items-center",
        "justify-content-center",
        className,
      ])}
    >
      <span className={"text-center"}>Image not available</span>
    </div>
  );
};

export default ImageNotAvailable;
