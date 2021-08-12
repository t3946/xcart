import React from "react";

export const TransactionHeader = ({ open, onClick }) => {
  return (
    <div className="transactions-header" onClick={onClick}>
      <div className="transactions-header-date">February 15, 2021 </div>
      <div className="transactions-header-card">Mastercard ****4383</div>
      <div className="transactions-header-name">Refund # KS-180043-HYP </div>
      <div className="transactions-header-price">(US$ 71.67)</div>
      <div className="transactions-header-arrow">
        <div className={`accordion-arrow ${open && "accordion-arrow-open"}`} />
      </div>
    </div>
  );
};
