import React, { useCallback } from "react";
import { FormSelect } from "@client/modules/account/components/shared/FormSelect";
import { FormInput } from "@client/modules/account/components/shared/FormInput";
import { useDropzone } from "react-dropzone";
import { fillArrayItemsOnOrderActions } from "@client/modules/account/utils/fill-array-items-order-actions";
import { returnSelectValues } from "@client/modules/account/ts/consts/order-actions-select.const";
import { FileDrop } from "@client/modules/account/components/shared/FileDrop";

interface ReturnOrReplaceItemProps {
  orderItem: any;
}

export const ReturnOrReplaceItems: React.FC<ReturnOrReplaceItemProps> = ({
  orderItem,
}) => {
  const onDrop = useCallback(([acceptedFile]) => {
    console.log(acceptedFile);
  }, []);

  return (
    <div>
      <div className="page-label order-actions-page-label">
        Return or replace items
      </div>
      <div className="order-product-list-header">
        <div className="order-product-list-header-sku">Item name / SKU </div>
        <div className="order-product-list-header-quantity">
          Return quantity
        </div>
        <div className="order-product-list-header-quantity-cancel">
          I would like to
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
                    <FormSelect
                      classes={{ group: "order-product-select-count" }}
                      value={{ value: 0, viewValue: 0 }}
                      items={fillArrayItemsOnOrderActions(e.amount)}
                      id={`${e.productcode}-amount`}
                    />
                  </div>
                  <div className="order-product-list-header-quantity-cancel">
                    <FormSelect
                      classes={{
                        group: "order-product-select-action",
                        selectHeader: "order-product-select-action-header",
                      }}
                      value={returnSelectValues[0]}
                      id={`${e.productcode}-action`}
                      items={returnSelectValues}
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
          placeholder="Explain why you would like to return products for a refund
          or replace them with the same or different products"
          classes={{
            input: "order-cancel-items-textarea-input",
            textArea: "order-cancel-items-textarea",
            group: "order-cancel-items-textarea-group",
          }}
        />
        <div className="order-cancel-items-disclosure-title attach-section">
          Please attach product images to speed up the RMA process:
        </div>
        <FileDrop onDrop={onDrop}>
          <button className="choose-file-btn" onClick={open}>
            Choose file
          </button>
        </FileDrop>

        <div className="order-cancel-items-disclosure">
          <div className="order-cancel-items-disclosure-title">Disclosure</div>
          <div className="order-cancel-items-disclosure-subtitle">
            1. Do not send the product back. Wait for the RMA form.
          </div>
          <div className="order-cancel-items-disclosure-subtitle">
            2. We can’T guarantee successful resolution of your request.
          </div>
          <div className="order-cancel-items-disclosure-subtitle">
            Our RMA department will work with the warehouse to resolve your
            problem.
          </div>
        </div>
        <button className="form-button submit-to-rma-dep-btn">
          Submit to RMA department
        </button>
      </div>
    </div>
  );
};
