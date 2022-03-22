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
          <p className="left-part">
            {group.cb_status_rel.xcart_order_human_readable_statuses.name}
          </p>
        </div>
        <div className="info-item-container">
          <p className="label-info-item right-part">Shipping status:</p>
          <p className="left-part">
            {group.dc_status_rel.xcart_order_human_readable_statuses.name}
          </p>
        </div>
      </div>
      <div className="total-group-right-side">
        {group.shipping_gross > 0 && (
          <div className="info-item-container info-item-container-spacing regular">
            <p className="">Regular shipping:</p>
            <p className="">
              US$ {parseFloat(group.shipping_gross)?.toFixed(2)}
            </p>
          </div>
        )}

        {groupTaxesTemplate()}

        <div className="info-item-container info-item-container-spacing subtotal">
          <p className="">Subtotal:</p>
          <p className="">US$ {parseFloat(group.total_gross)?.toFixed(2)}</p>
        </div>
      </div>
    </div>
  );
};
