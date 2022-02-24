import React from "react";
import SubmitCancelButtonsGroup from "@client/modules/account/components/shared/SubmitCancelButtonsGroup";
import { List } from "@client/modules/account/ts/types/list.type";

interface AddProductToListProps {
  info: List;
  onCancelClick: () => void;
  isAlreadyInList: boolean;
  product: any;
}

export const AddProductToList: React.FC<AddProductToListProps> = ({
  info,
  onCancelClick,
  isAlreadyInList,
  product,
}) => {
  const viewYourList = () => {
    window.location.assign(`/account/shopping-lists/${info.cacheUrl}`);
  };

  const text = isAlreadyInList ? "This item was already in" : "1 item added to";

  return (
    <div>
      <div className="add-product-to-list-label-container d-flex">
        <div className="add-product-to-list-label-text">{text}</div>
        <div className="add-product-to-list-name" onClick={viewYourList}>
          {info.name}
        </div>
      </div>
      <div className="add-product-to-list-content">
        <img
          src={product.image}
          className="add-product-to-list-content-img"
        />
        <div className="add-product-to-list-content-text">
          {product.product}
        </div>
      </div>
      <SubmitCancelButtonsGroup
        submitText="Continue shopping"
        cancelText="View Your List"
        cancelAdvancedClasses="add-product-to-list-btn"
        submitAdvancedClasses="add-product-to-list-btn"
        onCancel={viewYourList}
        groupAdvancedClasses={"add-product-to-list-btns"}
        onConfirm={onCancelClick}
      />
    </div>
  );
};
