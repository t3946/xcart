import classNames from "classnames";
import React from "react";
import { useHistory } from "react-router-dom";

interface MovedProductPlaceholderProps {
  label: string;
  id: string;
  productName: string;
}

export const MovedProductPlaceholder: React.FC<MovedProductPlaceholderProps> =
  ({ label, id, productName }) => {
    const movedContainerClasses = ["moved-product-container"];

    const history = useHistory();

    const redirectFromNewList = () => {
      let path = "/account/";
      if (label === "Shipping list") {
        path += "your-lists";
      } else {
        path += `your-lists/${id}`;
      }
      history.push(path);
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
