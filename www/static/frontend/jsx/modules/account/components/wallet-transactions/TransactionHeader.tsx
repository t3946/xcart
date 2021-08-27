import React from "react";

export const TransactionHeader = ({
  open,
  onClick,
  refund = undefined,
  transactionInfo,
}) => {
  const date = new Date(
    Number(transactionInfo.orderInfo.date)
  ).toLocaleDateString("en-EN", {
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
            <div className="transactions-header-card">
              {transactionInfo.cardInfo.card_type[0].toUpperCase() +
                transactionInfo.cardInfo.card_type.slice(1)}
              {` ****${transactionInfo.cardInfo.card_number.substr(
                transactionInfo.cardInfo.card_number.length - 4
              )} `}
            </div>
          </div>

          <div className="transactions-header-name">
            {refund ? "Refund" : "Receipt"}
            {` # ${transactionInfo.orderInfo.order_prefix}${transactionInfo.orderInfo.orderid}-${transactionInfo.orderInfo.order_type}`}
          </div>
        </div>

        <div
          className={`transactions-header-price ${
            refund && "transactions-header-price-refund"
          }`}
        >
          (US$ {transactionInfo.orderInfo.total})
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
