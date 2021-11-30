import React, { useEffect } from "react";
import { TransactionsList } from "../components/wallet-transactions/TransactionsList";
import StoreInterface from "@modules/account/ts/types/store.type";
import { useDispatch, useSelector } from "react-redux";
import { getTransaction } from "@redux/actions/account-actions/PaymentsActions";

export const Transactions: React.FC = () => {
  const transactions = useSelector(
    (e: StoreInterface) => e.payments.transactions
  );

  const dispatch = useDispatch();

  useEffect(() => {
    if (!transactions) {
      dispatch(getTransaction());
    }
  }, []);
  return (
    <div>
      <div className="page-label">Transactions</div>
      <div className="wallet-label">
        Refer below for your most recent transactions.
      </div>
      <TransactionsList transactions={transactions || []} />
    </div>
  );
};
