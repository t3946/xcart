import React from "react";
import Price from "@components/common/price/Price";

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
          <p>
            <Price price={taxValue} />
          </p>
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
            <p>Regular shipping:</p>
            <p>
              <Price price={group.shipping_gross} />
            </p>
          </div>
        )}

        {groupTaxesTemplate()}

        <div className="info-item-container info-item-container-spacing subtotal">
          <p>Subtotal:</p>
          <p>
            <Price price={group.total_gross} />
          </p>
        </div>
      </div>
    </div>
  );
};
