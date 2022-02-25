import React from "react";
import { TransactionItemsElem } from "@modules/account/components/wallet-transactions/TransactionItemsElem";
import { TransactionItemsListTotal } from "@modules/account/components/wallet-transactions/TransactionItemsListTotal";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";

export const TransactionItems = ({ refund = false, group, order }) => {
  const breakpoint = useSelector((e: StoreInterface) => e.main.breakpoint);

  return (
    <div>
      <div className="transaction-items-label transaction-items-sublabel">
        The items below are shipped from {group.shipping}
      </div>
      <div
        className={`transaction-items-list-header ${
          refund && "transaction-items-list-refund-header"
        }`}
      >
        <div className="transaction-items-sku-block">SKU</div>
        <div className="transaction-items-name-block">Item name</div>
        <div className="transaction-items-price-block">
          {breakpoint.is768 ? " Price x Qty" : "Price"}
        </div>
        <div className="transaction-items-qty-block">Qty ordered</div>
        <div className="transaction-items-extended-block">Extended</div>
      </div>
      {group.xcart_order_details.map((detail, i) => {
        return (
          <TransactionItemsElem
            breakpoint={breakpoint}
            orderGroupsItemInfo={detail}
            key={`transaction-item-${i}-${detail.itemid}`}
          />
        );
      })}
      {!refund && <TransactionItemsListTotal group={group} orderInfo={order} />}
    </div>
  );
};
