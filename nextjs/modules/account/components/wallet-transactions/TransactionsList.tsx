import React from "react";
import { TransactionItem } from "./TransactionItem";
import { TransactionItemRefund } from "./TransactionItemRefund";

interface IProps {
  orders: Record<any, any>[];
  cards: Record<any, any>[];
}

export const TransactionsList: React.FC<IProps> = (props) => {
  const { orders } = props;

  function getTransactionsTemplates() {
    const transactions = [];

    for (const order of orders) {
      for (const transaction of order.xcart_order_transactions) {
        //todo: TransactionItemRefund
        const Item =
          transaction.type === "refund"
            ? TransactionItem //TransactionItemRefund
            : TransactionItem;
        const card = null;

        transactions.push(
          <Item
            first={transactions.length === 0}
            order={order}
            transaction={transaction}
            card={card}
            key={`transaction-${transaction.id}`}
          />
        );
      }
    }

    return transactions;
  }

  return getTransactionsTemplates();
};
