import React from "react";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import { List } from "@modules/account/ts/types/list.type";

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
    window.location.assign(`/account/your-lists/${info.cache_url}`);
  };

  const productInfo = product || window.appData?.productInfo?.product;

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
          src={productInfo?.image}
          className="add-product-to-list-content-img"
        />
        <div className="add-product-to-list-content-text">
          {productInfo?.product}
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
