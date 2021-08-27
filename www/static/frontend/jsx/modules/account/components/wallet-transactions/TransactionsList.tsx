import React from "react";
import { TransactionItem } from "./TransactionItem";
import { TransactionItemRefund } from "./TransactionItemRefund";

export const TransactionsList = ({ transactions }) => {
  return (
    <div>
      {transactions.map((info, index) => {
        if (info.type === "refund") {
          return (
            <TransactionItemRefund first={index === 0} transactionInfo={info} />
          );
        }
        return <TransactionItem first={index === 0} transactionInfo={info} />;
      })}
    </div>
  );
};
