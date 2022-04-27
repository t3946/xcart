import React from "react";
import cn from "classnames";
import ChevronIcon from "@modules/icon/components/font-awesome/chevron-down/Regular";
import Price from "@components/common/price/Price";
import Styles from "@modules/account/components/wallet-transactions/TransactionHeader.module.scss";

export const TransactionHeader = ({
  open,
  onClick,
  refund = undefined,
  order,
  transaction,
  card,
}) => {
  const date = new Date(Number(order.date * 1000)).toLocaleDateString("en-EN", {
    month: "long",
    day: "2-digit",
    year: "numeric",
  });
  return (
    <div
      className={cn({ "transactions-header-refund": refund })}
      onClick={onClick}
    >
      <div
        className={`transactions-header flex-wrap flex-sm-nowrap ${
          refund && "transactions-header-refund"
        }`}
      >
        {/* <div className="transactions-header-main-block"> */}
        <div className={"transactions-header-left-block col-6 col-sm-5"}>
          <div className="transactions-header-date">{date}</div>
          {card && (
            <div className="transactions-header-card">
              {card.brand.toUpperCase()}
              {` **** ${card.last4} `}
            </div>
          )}
        </div>

        <div className="transactions-header-name order-1 col-12 col-sm-auto  order-sm-0">
          {refund ? "Refund" : "Receipt"}
          {` # ${order.order_prefix}${order.orderid}`}
        </div>
        {/* </div> */}

        <div
          className={`transactions-header-price col order-0 ${
            refund && "transactions-header-price-refund"
          }`}
        >
          <Price price={transaction.transaction_amount} />
        </div>
        <div className="transactions-header-arrow">
          <ChevronIcon
            className={cn(Styles.chevron, { [Styles.rotate]: open })}
          />
        </div>
      </div>
      <div className="transactions-mobile-header-arrow">
        <ChevronIcon
          className={cn(Styles.chevron, { [Styles.rotate]: open })}
        />
      </div>
    </div>
  );
};
