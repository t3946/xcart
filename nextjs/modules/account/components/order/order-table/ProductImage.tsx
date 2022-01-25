import React from "react";
import cn from "classnames";

import Styles from "@modules/account/components/order/order-table/ProductImage.module.scss";

interface IProps {
  image: string;
}

const ProductImage: React.FC<IProps> = ({ image }) => {
  return (
    <div
      style={{
        background: `url(${image}) left center / contain no-repeat`,
      }}
      className={cn(["d-none", "d-lg-block", Styles.estimateTableProductImage])}
    />
  );
};

export default ProductImage;
