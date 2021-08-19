import React, { useRef } from "react";
import { TransactionHeader } from "./TransactionHeader";
import { TransactionItemTopBlock } from "./TransactionItemTopBlock";
import { TransactionItemContactBlock } from "./TransactionItemContactBlock";
import { TransactionAddresses } from "./TransactionAddresses";
import { TransactionItems } from "./TransactionItems";
import { useAccordion } from "../../hooks/useAccordion";
import { useSelector } from "react-redux";
import { AccountStore } from "../../ts/types/account-store.type";

export const TransactionItemRefund = ({ transactionInfo, first }) => {
  const accordion = useAccordion(500);

  const breakpoint = useSelector((e: AccountStore) => e.main.breakpoint);

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
      </div>
    </div>
  );
};
