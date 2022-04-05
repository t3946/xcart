import React from "react";
import { TransactionItemsElem } from "@modules/account/components/wallet-transactions/TransactionItemsElem";
import { TransactionItemsListTotal } from "@modules/account/components/wallet-transactions/TransactionItemsListTotal";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";

export const TransactionItems = ({ refund = false, group, order }) => {
  const breakpoint = useSelector((e: StoreInterface) => e.main.breakpoint);

  return (
    <div>
      {!refund && (
        <div className="transaction-items-label transaction-items-sublabel">
          The items below are shipped from {group.shipping}
        </div>
      )}
      <div
        className={`transaction-items-list-header px-2 px-md-4 ${
          refund && "transaction-items-list-refund-header"
        }`}
      >
        <div className="col-lg-2 d-none d-lg-block">SKU</div>
        <div className="col-md-6 col-lg-5 text-start text-lg-center">
          Item name <span className="d-lg-none">/ SKU</span>
        </div>
        <div className="col-md-3 col-lg-2 d-none d-md-block text-center">
          Price
        </div>
        <div className="col-lg-1 d-none d-lg-block text-center">
          Qty ordered
        </div>
        <div className="col-md-3 col-lg-2 text-end">Extended</div>
      </div>
      {group.details.map((detail, i) => {
        return (
          <TransactionItemsElem
            refund={refund}
            breakpoint={breakpoint}
            orderGroupsItemInfo={detail}
            key={`transaction-item-${i}-${detail.itemid}`}
          />
        );
      })}
      {!refund && <TransactionItemsListTotal group={group} />}
    </div>
  );
};
