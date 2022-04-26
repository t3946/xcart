import * as React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/lists/item/Image.module.scss";
import ImageNotAvailable from "@components/common/image-not-available/ImageNotAvailable";

interface IProps {
  imgUrl?: string;
}

export const Image: React.FC<IProps> = function (props) {
  const { imgUrl } = props;

  function imageTemplate() {
    if (!imgUrl) {
      return <ImageNotAvailable className={Styles.imageNotAvailable} />;
    }

    return <img className={cn("mw-100", "mh-100")} src={imgUrl} alt={""} />;
  }

  return (
    <div
      className={cn(
        Styles.image,
        "flex-shrink-0",
        "d-flex",
        "justify-content-center"
      )}
    >
      {imageTemplate()}
    </div>
  );
};

export default Image;
