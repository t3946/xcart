import classNames from "classnames";
import React from "react";
import { useRouter } from "next/router";

interface MovedProductPlaceholderProps {
  label: string;
  cache: string;
  productName: string;
}

export const MovedProductPlaceholder: React.FC<
  MovedProductPlaceholderProps
> = ({ label, cache, productName }) => {
  const movedContainerClasses = [
    "moved-product-container",
    "w-100",
    "d-none",
    "d-md-block",
  ];
  const router = useRouter();
  const redirectFromNewList = () => {
    let path = "/";

    if (label === "Shipping list") {
      path += "shopping-lists";
    } else {
      path += `shopping-lists/${cache}`;
    }

    router.push(path);
  };

  return (
    <div className={classNames(movedContainerClasses)}>
      <div className="moved-product-name">{productName}</div>
      <div className="moved-product-content">
        <div className="d-flex">
          <img
            src={"/static/frontend/images/icons/account/check-mark-green.svg"}
          />
          <div className="moved-product-label">Moved to </div>
        </div>
        <div className="list-name w-auto ms-1" onClick={redirectFromNewList}>
          {label}
        </div>
      </div>
    </div>
  );
};
