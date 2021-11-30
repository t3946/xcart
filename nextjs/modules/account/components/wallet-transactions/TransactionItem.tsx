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

export const TransactionItem = ({ transactionInfo, first }) => {
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
        transactionInfo={transactionInfo}
      />
      <div
        className={`transaction-body ${
          accordion.open && "transaction-body-open"
        }`}
        style={{
          height: accordion.height,
        }}
        ref={accordion.ref}
      >
        <TransactionItemTopBlock
          transactionInfo={transactionInfo}
          componentRef={accordion.ref}
        />
        <TransactionItemContactBlock orderInfo={transactionInfo.orderInfo} />
        <TransactionAddresses orderInfo={transactionInfo.orderInfo} />
        <PurchaseOrderInformation />
        <div className="transaction-checkbox">
          <FormCheckBox
            label={
              "I agree to be responsible for custom duties, CODs, and other charges associated with bringing goods to Canada."
            }
            value={true}
            name={"is_default"}
            handleChange={null}
          />
        </div>

        <div className="transaction-items-label">
          Refund issued for the following items
        </div>
        {transactionInfo.orderInfo.orderGroups.map((e) => {
          return <TransactionItems info={e} />;
        })}
        <div className="transaction-total-container total-shipping">
          <div className="total-left-side" />
          <div className="total-right-side total-group-right-side total-right-side">
            <div className="info-item-container info-item-container-spacing">
              <p className="total-text total-text-left"> Total Items Cost:</p>
              <p className="total-text">
                US$ {transactionInfo.orderInfo.shipping_gross}
              </p>
            </div>
            <div className="info-item-container info-item-container-spacing regular">
              <p className="total-text total-text-left">
                {" "}
                Total Shipping Cost:
              </p>
              <p className="total-text">
                US$ {transactionInfo.orderInfo.shipping_gross}
              </p>
            </div>
            <div className="info-item-container info-item-container-spacing tax">
              <div className="total-text total-text-left">Total Sales Tax:</div>
              <div className="total-text">
                US$ {transactionInfo.orderInfo.total_pst}
              </div>
            </div>
            <div className="info-item-container info-item-container-spacing tax">
              <p className="total-text total-text-left">Total VAT Tax: </p>
              <p className="total-text">
                US$ {transactionInfo.orderInfo.total_tax}
              </p>
            </div>
            <div className="info-item-container info-item-container-spacing subtotal">
              <p className="total-text total-text-left">GRAND TOTAL:</p>
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
