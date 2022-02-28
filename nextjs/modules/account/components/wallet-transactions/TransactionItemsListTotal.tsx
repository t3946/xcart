import React from "react";

export const TransactionItemsListTotal = (props) => {
  const { group } = props;

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
          <p className="left-part">{group.paymentStatus}</p>
        </div>
        <div className="info-item-container">
          <p className="label-info-item right-part">Shipping status:</p>
          <p className="left-part">{group.shippingStatus}</p>
        </div>
      </div>
      <div className="total-group-right-side">
        <div className="info-item-container info-item-container-spacing regular">
          <p className=""> Regular shipping:</p>
          <p className="">US$ {parseFloat(group.shipping_gross)?.toFixed(2)}</p>
        </div>
        <div className="info-item-container info-item-container-spacing tax">
          <div className="">Sales Tax:</div>
          <div className="">US$ {parseFloat(group.total_pst)?.toFixed(2)}</div>
        </div>
        <div className="info-item-container info-item-container-spacing tax">
          <p className="">VAT Tax: </p>
          <p className="">US$ {parseFloat(group.total_tax)?.toFixed(2)}</p>
        </div>

        {groupTaxesTemplate()}

        <div className="info-item-container info-item-container-spacing subtotal">
          <p className="">Subtotal:</p>
          <p className="">US$ {parseFloat(group.total_gross)?.toFixed(2)}</p>
        </div>
      </div>
    </div>
  );
};
