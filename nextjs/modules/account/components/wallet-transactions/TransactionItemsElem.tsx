import React from "react";

export const TransactionItemsElem = ({
  orderGroupsItemInfo,
  breakpoint,
  refund = false,
}) => {
  return (
    <div className="transaction-items-list-item flex-wrap px-2 px-md-4 flex-md-nowrap">
      <div className="col-lg-2 d-none d-lg-block text-success">
        {orderGroupsItemInfo.productcode}
      </div>
      <div className="col-12 col-md-6 col-lg-5">
        {orderGroupsItemInfo.product}
        <div className="d-lg-none text-success">
          {orderGroupsItemInfo.productcode}
        </div>
      </div>
      <div className="col-md-3 col-lg-2 text-center">
        US${" "}
        {breakpoint.is768
          ? `${parseFloat(orderGroupsItemInfo.price)?.toFixed(2)} x ${
              orderGroupsItemInfo.amount
            }`
          : parseFloat(orderGroupsItemInfo.price)?.toFixed(2)}
        <span className="d-none d-md-inline d-lg-none">
          {" "}
          x {orderGroupsItemInfo.amount}
        </span>
      </div>
      <div className="text-center d-md-none d-lg-block col-lg-1">
        <span className="d-md-none">x </span>
        {orderGroupsItemInfo.amount}
      </div>
      <div className="col-md-3 col-lg-2 text-end">
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
