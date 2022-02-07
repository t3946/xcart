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

export const TransactionItem: React.FC<IProps> = (props) => {
  const { order, transaction, card, first } = props;
  const accordion = useAccordion(500);
  const breakpoint = useSelector((e: StoreInterface) => e.main.breakpoint);

  return (
    <div className="transaction">
      {(first || breakpoint.is768) && (
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
        <PurchaseOrderInformation />
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
              <p className="total-text">US$ {order.shipping_gross}</p>
            </div>
            <div className="info-item-container info-item-container-spacing regular">
              <p className="total-text total-text-left">
                {" "}
                Total Shipping Cost:
              </p>
              <p className="total-text">US$ {order.shipping_gross}</p>
            </div>
            <div className="info-item-container info-item-container-spacing tax">
              <div className="total-text total-text-left">Total Sales Tax:</div>
              <div className="total-text">US$ {order.total_pst}</div>
            </div>
            <div className="info-item-container info-item-container-spacing tax">
              <p className="total-text total-text-left">Total VAT Tax: </p>
              <p className="total-text">US$ {order.total_tax}</p>
            </div>
            <div className="info-item-container info-item-container-spacing subtotal">
              <p className="total-text total-text-left">GRAND TOTAL:</p>
              <p className="total-text">US$ {order.total_gross}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
