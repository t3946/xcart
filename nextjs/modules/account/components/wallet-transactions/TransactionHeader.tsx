import React from "react";

export const TransactionHeader = ({
  open,
  onClick,
  refund = undefined,
  order,
  card,
}) => {
  const date = new Date(Number(order.date)).toLocaleDateString("en-EN", {
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
            {` # ${order.order_prefix}${order.orderid}-${order.order_type}`}
          </div>
        </div>

        <div
          className={`transactions-header-price ${
            refund && "transactions-header-price-refund"
          }`}
        >
          (US$ {order.total})
        </div>
        <div className="transactions-header-arrow">
          <div
            className={`accordion-arrow ${open && "accordion-arrow-open"}`}
          />
        </div>
      </div>
      <div className="transactions-mobile-header-arrow">
        <div className={`accordion-arrow ${open && "accordion-arrow-open"}`} />
      </div>
    </div>
  );
};
