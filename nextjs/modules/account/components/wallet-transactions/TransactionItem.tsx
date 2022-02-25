import React from "react";
import { TransactionHeader } from "./TransactionHeader";
import { useAccordion } from "@modules/account/hooks/useAccordion";
import { TransactionItemTopBlock } from "./TransactionItemTopBlock";
import { TransactionItemContactBlock } from "./TransactionItemContactBlock";
import { TransactionAddresses } from "@modules/account/components/wallet-transactions/TransactionAddresses";
import { TransactionItems } from "./TransactionItems";
import { PurchaseOrderInformation } from "./PurchaseOrderInformation";
import { FormCheckBox } from "../shared/FormCheckBox";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";

interface IProps {
  order: any;
  transaction: any;
  card: any;
  first: any;
}

function isCompleted(transaction: any) {
  if (
    transaction.type === "refund" &&
    transaction.transaction_status === "refunded"
  ) {
    return true;
  }

  if (
    transaction.type === "authorization" &&
    (transaction.transaction_status === "captured" ||
      transaction.transaction_status === "voided")
  ) {
    return true;
  }

  if (
    transaction.type === "capture" &&
    transaction.transaction_status === "completed"
  ) {
    return true;
  }

  return false;
}

export const TransactionItem: React.FC<IProps> = (props) => {
  const { order, transaction, card, first } = props;
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
          <p className="total-text">US$ {taxValue}</p>
        </div>
      );
    }

    return templates;
  }

  return (
    <div className="transaction">
      {(isCompleted(transaction) || breakpoint.is768) && (
        <div className={"transactions-completed-header"}>Completed</div>
      )}

      <TransactionHeader
        onClick={accordion.onItemClick}
        open={accordion.open}
        card={card}
        order={order}
      />

      <div
        className={`transaction-body position-relative ${
          accordion.open && "transaction-body-open"
        }`}
        style={{
          height: accordion.height,
        }}
        ref={accordion.ref}
      >
        <TransactionItemTopBlock order={order} componentRef={accordion.ref} />
        <TransactionItemContactBlock order={order} />
        <TransactionAddresses order={order} />
        {order.extra && <PurchaseOrderInformation order={order} />}
        {order.non_us_confirmation === "Y" && (
          <div className="transaction-checkbox">
            <FormCheckBox
              label={
                "I agree to be responsible for custom duties, CODs, and other charges associated with bringing goods to Canada."
              }
              value={true}
              name={"is_default"}
              handleChange={() => {}}
            />
          </div>
        )}

        <div className="transaction-items-label">
          Refund issued for the following items
        </div>
        {order.groups.map((group, i) => {
          return (
            <TransactionItems
              group={group}
              order={order}
              key={`transactionitems-${i}`}
            />
          );
        })}
        <div className="transaction-total-container total-shipping">
          <div className="total-left-side" />
          <div className="total-right-side total-group-right-side total-right-side">
            <div className="info-item-container info-item-container-spacing">
              <p className="total-text total-text-left"> Total Items Cost:</p>
              <p className="total-text">US$ {order.subtotal}</p>
            </div>
            <div className="info-item-container info-item-container-spacing regular">
              <p className="total-text total-text-left">
                {" "}
                Total Shipping Cost:
              </p>
              <p className="total-text">US$ {order.totalShipping}</p>
            </div>

            {orderTaxesTemplate()}

            <div className="info-item-container info-item-container-spacing subtotal">
              <p className="total-text total-text-left">GRAND TOTAL:</p>
              <p className="total-text">US$ {order.total}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
