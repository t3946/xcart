import React from "react";

export const TransactionItemsListTotal = ({ group, orderInfo }) => {
  function groupTaxesTemplate() {
    const templates = [];

    for (const taxesKey in group.taxes) {
      const taxValue = group.taxes[taxesKey];

      templates.push(
        <div
          className="info-item-container info-item-container-spacing tax"
          key={`group-tax-${taxesKey}`}
        >
          <p>{taxesKey}: </p>
          <p>US$ {taxValue}</p>
        </div>
      );
    }

    return templates;
  }
  return (
    <div className="transaction-total-container">
      <div className="total-left-side">
        <div className="info-item-container">
          <p className="label-info-item right-part">Payment status:</p>
          <p className="left-part">{orderInfo.a2b_status}</p>
        </div>
        <div className="info-item-container">
          <p className="label-info-item right-part">Shipping status:</p>
          <p className="left-part">{orderInfo.a2c_status}</p>
        </div>
      </div>
      <div className="total-group-right-side">
        <div className="info-item-container info-item-container-spacing regular">
          <p className=""> Regular shipping:</p>
          <p className="">US$ {group.shipping_gross}</p>
        </div>

        {groupTaxesTemplate()}

        <div className="info-item-container info-item-container-spacing subtotal">
          <p className="">Subtotal:</p>
          <p className="">US$ {group.total_gross}</p>
        </div>
      </div>
    </div>
  );
};
