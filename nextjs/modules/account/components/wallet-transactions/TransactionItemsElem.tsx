import React from "react";

export const TransactionItemsElem = ({
  orderGroupsItemInfo,
  breakpoint,
  refund = false,
}) => {
  return (
    <div className="transaction-items-list-item">
      <div className="transaction-items-sku-block">
        {orderGroupsItemInfo.productcode}
      </div>
      <div className="transaction-items-name-block">
        {orderGroupsItemInfo.product}
      </div>
      <div className="transaction-items-price-block">
        US${" "}
        {breakpoint.is768
          ? `${orderGroupsItemInfo.price} x ${orderGroupsItemInfo.amount}`
          : orderGroupsItemInfo.price}
      </div>
      <div className="transaction-items-qty-block">
        {orderGroupsItemInfo.amount}
      </div>
      <div className="transaction-items-extended-block">
        {refund
          ? `(US$ ${(
              orderGroupsItemInfo.price * orderGroupsItemInfo.amount
            )?.toFixed(2)})`
          : `US$ ${(
              orderGroupsItemInfo.price * orderGroupsItemInfo.amount
            )?.toFixed(2)}`}
      </div>
    </div>
  );
};
