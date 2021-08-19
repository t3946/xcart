import React from "react";
import { TransactionHeader } from "./TransactionHeader";
import { useAccordion } from "@client/modules/account/hooks/useAccordion";
import { TransactionItemTopBlock } from "./TransactionItemTopBlock";
import { TransactionItemContactBlock } from "./TransactionItemContactBlock";
import { TransactionAddresses } from "@client/modules/account/components/wallet-transactions/TransactionAddresses";
import { TransactionItems } from "./TransactionItems";
import { PurchaseOrderInformation } from "./PurchaseOrderInformation";
import { FormCheckBox } from "../shared/FormCheckBox";
import { useSelector } from "react-redux";
import { AccountStore } from "../../ts/types/account-store.type";

export const TransactionItem = ({ transactionInfo, first }) => {
  const accordion = useAccordion(500);

  const breakpoint = useSelector((e: AccountStore) => e.main.breakpoint);

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
      </div>
    </div>
  );
};
