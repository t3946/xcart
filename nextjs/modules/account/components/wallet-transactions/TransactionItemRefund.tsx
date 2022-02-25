import React from "react";
import { TransactionHeader } from "./TransactionHeader";
import { TransactionItemTopBlock } from "./TransactionItemTopBlock";
import { TransactionItemContactBlock } from "./TransactionItemContactBlock";
import { TransactionAddresses } from "./TransactionAddresses";
import { TransactionItems } from "./TransactionItems";
import { useAccordion } from "../../hooks/useAccordion";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";

export const TransactionItemRefund = ({ order, transaction, card, first }) => {
  const accordion = useAccordion(500);
  const breakpoint = useSelector((e: StoreInterface) => e.main.breakpoint);

  function orderTaxesTemplate() {
    const templates = [];

    for (const taxesKey in order.taxes) {
      const taxValue = order.taxes[taxesKey];

      templates.push(
        <div
          className="info-item-container info-item-container-spacing tax"
          key={`order-tax-${taxesKey}`}
        >
          <p className="total-text total-text-left">Total {taxesKey}:</p>
          <p className="total-text">(US$ {taxValue})</p>
        </div>
      );
    }

    return templates;
  }

  return (
    <div>
      {(first || breakpoint.is768) && (
        <div className={"transactions-completed-header"}>Completed</div>
      )}
      <TransactionHeader
        onClick={accordion.onItemClick}
        open={accordion.open}
        refund={true}
        order={order}
        transaction={transaction}
        card={card}
      />
      <div
        className={`transaction-body transaction-body-refund ${
          accordion.open && "transaction-body-open"
        }`}
        style={{
          height: accordion.height,
        }}
        ref={accordion.ref}
      >
        <TransactionItemTopBlock
          order={order}
          refund
          componentRef={accordion.ref}
        />
        <TransactionItemContactBlock order={order} refund />
        <TransactionAddresses order={order} refund />

        <div className="transaction-items-label">
          Refund issued for the following items
        </div>
        {order.groups.map((group, i) => {
          return (
            <TransactionItems
              refund={true}
              group={group}
              order={order}
              key={`transactionitems-${i}`}
            />
          );
        })}
        <div className="transaction-total-container">
          <div className="total-left-side" />
          <div className="total-right-side total-group-right-side">
            <div className="info-item-container info-item-container-spacing regular">
              <p className="total-text  total-text-left">
                Shipping Cost Refund:{" "}
              </p>
              <p className="total-text">
                (US$ {order.shipping_gross?.toFixed(2)})
              </p>
            </div>
            <div className="info-item-container info-item-container-spacing tax">
              <div className="total-text  total-text-left">
                Sales Tax Refund:
              </div>
              <div className="total-text">
                (US$ {order.total_pst?.toFixed(2)})
              </div>
            </div>
            <div className="info-item-container info-item-container-spacing tax">
              <p className="total-text  total-text-left">VAT Tax Refund: </p>
              <p className="total-text">(US$ {order.total_tax?.toFixed(2)})</p>
            </div>

            {orderTaxesTemplate()}

            <div className="info-item-container info-item-container-spacing subtotal">
              <p className="total-text total-text-left">Total Refund: </p>
              <p className="total-text">
                (US$ {order.total_gross?.toFixed(2)})
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
