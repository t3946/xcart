import React from "react";
import SubmitCancelButtonsGroup from "@client/modules/account/components/shared/SubmitCancelButtonsGroup";
import { List } from "@client/modules/account/ts/types/list.type";
import useSelectorAccount from "@client/modules/account/hooks/useSelectorAccount";
import Styles from "@client/jsx/modules/account/components/lists/AddProductToList.module.scss";
import cn from "classnames";

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
  const product_info = useSelectorAccount((e) => e.site.product_info);

  function viewYourList() {
    window.location.assign(`/account/shopping-lists/${info.product_list_id}`);
  }

  const text = isAlreadyInList ? "This item was already in" : "1 item added to";

  console.log("AddProductToList", {product});

  return (
    <div>
      <div className="add-product-to-list-label-container d-flex">
        <div className="add-product-to-list-label-text">{text}</div>
        <div className="add-product-to-list-name" onClick={viewYourList}>
          {info.name}
        </div>
      </div>
      <div className="add-product-to-list-content">
        <div className={cn(Styles.imageContainer, "d-flex", "justify-content-center", "flex-shrink-0")}>
          <img
            src={product_info?.image}
            className={Styles.image}
            alt={""}
          />
        </div>

        <div className={cn(Styles.name, "ms-3")}>
          {`${product.group_mask} ${product.product}`}
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
