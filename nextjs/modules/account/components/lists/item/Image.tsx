import * as React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/lists/item/Image.module.scss";

interface IProps {
  imgUrl: string;
}

export const Image: React.FC<IProps> = function (props) {
  const { imgUrl } = props;

  return (
    <div className={cn(Styles.image, "flex-shrink-0")}>
      <img className={cn("w-100")} src={imgUrl} alt={""} />
    </div>
  );
};

export default Image;
