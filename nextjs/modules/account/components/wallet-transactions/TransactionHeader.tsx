import React from "react";
import cn from "classnames";
import ChevronIcon from "@modules/icon/components/font-awesome/chevron-down/Regular";

import Styles from "@modules/account/components/wallet-transactions/TransactionHeader.module.scss";

export const TransactionHeader = ({
  open,
  onClick,
  refund = undefined,
  order,
  card,
}) => {
  const date = new Date(Number(order.date * 1000)).toLocaleDateString("en-EN", {
    month: "long",
    day: "2-digit",
    year: "numeric",
  });
  return (
    <div onClick={onClick}>
      <div
        className={`transactions-header ${
          refund && "transactions-header-refund"
        }`}
      >
        <div className="transactions-header-main-block">
          <div className={"transactions-header-left-block"}>
            <div className="transactions-header-date">{date}</div>
            {card && (
              <div className="transactions-header-card">
                {card.brand.toUpperCase()}
                {` **** ${card.last4} `}
              </div>
            )}
          </div>

          <div className="transactions-header-name">
            {refund ? "Refund" : "Receipt"}
            {` # ${order.order_prefix}${order.orderid}`}
          </div>
        </div>

        <div
          className={`transactions-header-price ${
            refund && "transactions-header-price-refund"
          }`}
        >
          {refund ? `(US$ ${order.total})` : `US$ ${order.total}`}
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
