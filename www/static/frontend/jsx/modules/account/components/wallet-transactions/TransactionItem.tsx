import React from "react";
import { TransactionHeader } from "./TransactionHeader";
import { useAccordion } from "@client/modules/account/hooks/useAccordion";
import { TransactionItemTopBlock } from "./TransactionItemTopBlock";
import { TransactionItemContactBlock } from "./TransactionItemContactBlock";
import { TransactionAddresses } from "@client/modules/account/components/wallet-transactions/TransactionAddresses";

export const TransactionItem = () => {
  const accordion = useAccordion();

  return (
    <div>
      <TransactionHeader
        onClick={accordion.onItemClick}
        open={accordion.open}
      />
      <div
        className="transaction-body"
        style={{
          height: accordion.height,
        }}
        ref={accordion.ref}
      >
        <TransactionItemTopBlock />
        <TransactionItemContactBlock />
        <TransactionAddresses />
      </div>
    </div>
  );
};
