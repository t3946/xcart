import React from "react";
import { FormSelect } from "@client/modules/account/components/shared/FormSelect";
import { FormInput } from "@client/modules/account/components/shared/FormInput";
import { fillArrayItemsOnOrderActions } from "@client/modules/account/utils/fill-array-items-order-actions";

interface CancelItemsProps {
  orderItem: any;
}

export const CancelItems: React.FC<CancelItemsProps> = ({ orderItem }) => {
  return (
    <div>
      <div className="page-label order-actions-page-label">Cancel items</div>
      <div className="order-product-list-header">
        <div className="order-product-list-header-sku">Item name / SKU </div>
        <div className="order-product-list-header-quantity">
          Quantity Ordered
        </div>
        <div className="order-product-list-header-quantity-cancel">
          Quantity to cancel
        </div>
      </div>
      <div className="order-product-list-body">
        <div className="order-products">
          {orderItem.orderGroups.map((group) => {
            return group.orderGroupsItems.map((e) => {
              return (
                <div className="order-product">
                  <div className="order-product-list-header-sku">
                    <div className="order-item-body-product-name">
                      {e.product}
                    </div>
                    <div className="order-item-body-product-sku">
                      {e.productcode}
                    </div>
                  </div>
                  <div className="order-product-list-header-quantity">
                    {e.amount}
                  </div>
                  <div className="order-product-list-header-quantity-cancel">
                    <FormSelect
                      classes={{ group: "order-product-select-count" }}
                      value={{ value: 0, viewValue: 0 }}
                      items={fillArrayItemsOnOrderActions(e.amount)}
                      id={e.productcode}
                    />
                  </div>
                </div>
              );
            });
          })}
        </div>
        <FormInput
          inputType="textarea"
          name={"aw"}
          handleChange={null}
          value={null}
          id={"132"}
          placeholder="Explain why you would like to cancel items"
          classes={{
            input: "order-cancel-items-textarea-input",
            textArea: "order-cancel-items-textarea",
            group: "order-cancel-items-textarea-group",
          }}
        />
        <button className="form-button request-cancellation-btn">
          REQUEST CANCELLATION
        </button>
        <div className="order-cancel-items-disclosure">
          <div className="order-cancel-items-disclosure-title">Disclosure</div>
          <div className="order-cancel-items-disclosure-subtitle">
            We’ll try our best to cancel the items, however cancellation is not
            guaranteed.
          </div>
        </div>
      </div>
    </div>
  );
};
