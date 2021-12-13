import classNames from "classnames";
import React from "react";
import { useRouter } from "next/router";

interface MovedProductPlaceholderProps {
  label: string;
  id: string;
  productName: string;
}

export const MovedProductPlaceholder: React.FC<
  MovedProductPlaceholderProps
> = ({ label, id, productName }) => {
  const movedContainerClasses = ["moved-product-container"];
  const router = useRouter();
  const redirectFromNewList = () => {
    let path = "/";

    if (label === "Shipping list") {
      path += "shopping-lists";
    } else {
      path += `shopping-lists/${id}`;
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
          <div className="moved-product-label">Moved to</div>
        </div>

        <div className="list-name" onClick={redirectFromNewList}>
          {label}
        </div>
      </div>
    </div>
  );
};
