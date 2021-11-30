import React  from "react";
import { TransactionHeader } from "./TransactionHeader";
import { TransactionItemTopBlock } from "./TransactionItemTopBlock";
import { TransactionItemContactBlock } from "./TransactionItemContactBlock";
import { TransactionAddresses } from "./TransactionAddresses";
import { TransactionItems } from "./TransactionItems";
import { useAccordion } from "../../hooks/useAccordion";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";

export const TransactionItemRefund = ({ transactionInfo, first }) => {
  const accordion = useAccordion(500);

  const breakpoint = useSelector((e: StoreInterface) => e.main.breakpoint);

  return (
    <div>
      {(first || breakpoint.is768) && (
        <div className={"transactions-completed-header"}>Completed</div>
      )}
      <TransactionHeader
        onClick={accordion.onItemClick}
        open={accordion.open}
        refund={true}
        transactionInfo={transactionInfo}
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
          transactionInfo={transactionInfo}
          refund
          componentRef={accordion.ref}
        />
        <TransactionItemContactBlock
          orderInfo={transactionInfo.orderInfo}
          refund
        />
        <TransactionAddresses orderInfo={transactionInfo.orderInfo} refund />

        <div className="transaction-items-label">
          Refund issued for the following items
        </div>
        {transactionInfo.orderInfo.orderGroups.map((e) => {
          return <TransactionItems refund info={e} />;
        })}
        <div className="transaction-total-container">
          <div className="total-left-side" />
          <div className="total-right-side total-group-right-side">
            <div className="info-item-container info-item-container-spacing regular">
              <p className="total-text  total-text-left">
                Shipping Cost Refund:{" "}
              </p>
              <p className="total-text">
                US$ {transactionInfo.orderInfo.shipping_gross}
              </p>
            </div>
            <div className="info-item-container info-item-container-spacing tax">
              <div className="total-text  total-text-left">
                Sales Tax Refund:
              </div>
              <div className="total-text">
                US$ {transactionInfo.orderInfo.total_pst}
              </div>
            </div>
            <div className="info-item-container info-item-container-spacing tax">
              <p className="total-text  total-text-left">VAT Tax Refund: </p>
              <p className="total-text">
                US$ {transactionInfo.orderInfo.total_tax}
              </p>
            </div>
            <div className="info-item-container info-item-container-spacing subtotal">
              <p className="total-text total-text-left">Total Refund: </p>
              <p className="total-text">
                US$ {transactionInfo.orderInfo.total_gross}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
