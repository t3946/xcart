import React from "react";
import SubmitCancelButtonsGroup from "@client/modules/account/components/shared/SubmitCancelButtonsGroup";

interface AddProductToListProps {
  info: any;
}

export const AddProductToList: React.FC<AddProductToListProps> = ({ info }) => {
  return (
    <div>
      <div className="add-product-to-list-label-container d-flex">
        <div className="add-product-to-list-label-text">1 item added to</div>
        <div className="add-product-to-list-name">{info.name}</div>
      </div>
      <div className="add-product-to-list-content">
        <img
          src={window.appData.product_info.product.image}
          className="add-product-to-list-content-img"
        />
        <div className="add-product-to-list-content-text">
          Scotch Double Sided Tape with Dispenser, Narrow Width, Engineered for
          Holding, 1/2 x 250 Inches (136)
        </div>
      </div>
      <SubmitCancelButtonsGroup
        submitText="Continue shopping"
        cancelText="View Your List"
        cancelAdvancedClasses="add-product-to-list-btn"
        submitAdvancedClasses="add-product-to-list-btn"
        onCancel={() => {
          console.log(1);
        }}
        groupAdvancedClasses={"add-product-to-list-btns"}
        onConfirm={null}
      />
    </div>
  );
};
