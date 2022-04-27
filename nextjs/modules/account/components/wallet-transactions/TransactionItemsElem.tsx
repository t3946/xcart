import React from "react";
import Price from "@components/common/price/Price";

export const TransactionItemsElem = ({
  orderGroupsItemInfo,
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
        <Price price={orderGroupsItemInfo.price} />
        <span>{` x ${orderGroupsItemInfo.amount}`}</span>
      </div>
      <div className="text-center d-md-none d-lg-block col-lg-1">
        <span className="d-md-none">x </span>
        {orderGroupsItemInfo.amount}
      </div>
      <div className="col-md-3 col-lg-2 text-end">
        <Price
          price={orderGroupsItemInfo.price * orderGroupsItemInfo.amount}
          refund={refund}
        />
      </div>
    </div>
  );
};
