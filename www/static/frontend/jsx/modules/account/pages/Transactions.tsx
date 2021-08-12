import React from "react";
import { TransactionsList } from "../components/wallet-transactions/TransactionsList";

export const Transactions: React.FC = () => {
  return (
    <div>
      <div className="page-label">Transactions</div>
      <div className="wallet-label">
        Refer below for your most recent transactions.
      </div>
      <TransactionsList />
    </div>
  );
};
