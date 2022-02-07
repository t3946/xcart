import React from "react";
import { TransactionsList } from "../components/wallet-transactions/TransactionsList";
import StoreInterface from "@modules/account/ts/types/store.type";
import { useSelector } from "react-redux";

interface IProps {
  orders: Record<any, any>[];
  cards: Record<any, any>[];
}

export const Transactions: React.FC<IProps> = (props) => {
  const { orders, cards } = props;
  //todo: old transactions
  const oldTransactions = useSelector(
    (e: StoreInterface) => e.payments.transactions
  );

  console.log({ oldTransactions });

  return (
    <div>
      <div className="page-label">Transactions</div>

      <div className="wallet-label">
        Refer below for your most recent transactions.
      </div>

      <TransactionsList orders={orders} cards={cards} />
    </div>
  );
};
