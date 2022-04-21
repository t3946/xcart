import React from "react";
import { TransactionHeader } from "./TransactionHeader";
import { useAccordion } from "@modules/account/hooks/useAccordion";
import { TransactionItemTopBlock } from "./TransactionItemTopBlock";
import { TransactionItemContactBlock } from "./TransactionItemContactBlock";
import { TransactionAddresses } from "@modules/account/components/wallet-transactions/TransactionAddresses";
import { TransactionItems } from "./TransactionItems";
import { PurchaseOrderInformation } from "./PurchaseOrderInformation";
import { FormCheckBox } from "../shared/FormCheckBox";
import cn from "classnames";

interface IProps {
  order: any;
  transaction: any;
  card: any;
  header: string;
}

export const TransactionItem: React.FC<IProps> = (props) => {
  const { order, card, header, transaction } = props;
  const accordion = useAccordion(500);

  function orderTaxesTemplate() {
    const templates = [];

    for (const taxesKey in order.taxes) {
      const taxValue = parseFloat(order.taxes[taxesKey]).toFixed(2);

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
      <div className={"transactions-completed-header d-md-none"}>{header}</div>

      <TransactionHeader
        onClick={accordion.onItemClick}
        open={accordion.open}
        card={card}
        order={order}
        transaction={transaction}
      />

      <div
        className={cn(`transaction-body position-relative`, {
          "border-bottom-0": !accordion.open,
        })}
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
              disabled={true}
            />
          </div>
        )}

        <div className="transaction-items-label">Products ordered</div>
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
              <p className="total-text">
                US$ {parseFloat(order.subtotal).toFixed(2)}
              </p>
            </div>
            <div className="info-item-container info-item-container-spacing regular">
              <p className="total-text total-text-left">Total Shipping Cost:</p>
              <p className="total-text">
                US$ {parseFloat(order.shipping_cost).toFixed(2)}
              </p>
            </div>

            {orderTaxesTemplate()}

            <div className="info-item-container info-item-container-spacing subtotal fw-bold">
              <p className="total-text total-text-left">GRAND TOTAL:</p>
              <p className="total-text">US$ {parseFloat(order.total).toFixed(2)}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
