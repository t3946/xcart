import React from "react";
import { TransactionItemsListTotal } from "@client/modules/account/components/wallet-transactions/TransactionItemsListTotal";

interface ProductsOrderedItemProps {
  orderItem: any;
}

export const ProductsOrderedItem: React.FC<ProductsOrderedItemProps> = ({
  orderItem,
}) => {
  return (
    <div>
      <div className="products-order-item-title">
        The items below are shipped from Ogdensburg, NY, US
      </div>
      <div className="products-order-item-header">
        <div className={"products-order-item-header-sku"}>Item name / SKU </div>
        <div className={"products-order-item-header-price"}>Price </div>
        <div className={"products-order-item-header-qty"}>Qty ordered</div>
        <div className={"products-order-item-header-extended"}>Extended</div>
      </div>
      {orderItem.orderGroupsItems.map((e) => {
        return (
          <div className="products-order-item">
            <div className="order-item-body-product-left-part products-order-item-header-sku">
              <img
                className="order-item-body-product-img"
                src="https://img2.wtftime.ru/store/2020/11/19/EYGJdrlu_amp_big.jpg"
              />
              <div>
                <a className="order-item-body-product-name">
                  Ecstasy Crafts Architextures Treasures - Wooden Corkscrew
                </a>
                <div className="order-item-body-product-sku">ECS-7G25093</div>
              </div>
            </div>
            <div className="products-order-item-header-price">US$ 2.85 </div>
            <div className="products-order-item-header-qty">2</div>
            <div className="products-order-item-header-extended">US$ 2.85</div>
          </div>
        );
      })}
      <TransactionItemsListTotal orderInfo={orderItem} />
    </div>
  );
};
