import React from "react";
import { TransactionItem } from "./TransactionItem";
import { TransactionItemRefund } from "./TransactionItemRefund";

function isCompleted(transaction: any) {
  if (
    transaction.type === "refund" &&
    transaction.transaction_status === "refunded"
  ) {
    return true;
  }

  if (
    transaction.type === "authorization" &&
    (transaction.transaction_status === "captured" ||
      transaction.transaction_status === "voided")
  ) {
    return true;
  }

  if (
    transaction.type === "capture" &&
    transaction.transaction_status === "completed"
  ) {
    return true;
  }

  return false;
}

interface IProps {
  orders: Record<any, any>[];
  cards: Record<any, any>[];
}

export const TransactionsList: React.FC<IProps> = (props) => {
  const { orders } = props;

  function getTransactionsTemplates() {
    const groups: any = {
      completed: [],
      pending: [],
      cancelled: [],
    };

    for (const order of orders) {
      for (const transaction of order.xcart_order_transactions) {
        //todo: TransactionItemRefund
        const Item =
          transaction.type === "refund"
            ? TransactionItemRefund
            : TransactionItem;
        const card = null;

        if (isCompleted(transaction)) {
          groups.completed.push(
            <Item
              header={"Completed"}
              order={order}
              transaction={transaction}
              card={card}
              key={`transaction-${transaction.id}`}
            />
          );
        } else if (transaction.transaction_status === "pending") {
          groups.pending.push(
            <Item
              header={"Pending"}
              order={order}
              transaction={transaction}
              card={card}
              key={`transaction-${transaction.id}`}
            />
          );
        } else {
          groups.cancelled.push(
            <Item
              header={"Cancelled"}
              order={order}
              transaction={transaction}
              card={card}
              key={`transaction-${transaction.id}`}
            />
          );
        }
      }
    }

    return (
      <div>
        {groups.completed.length > 0 && (
          <>
            <div className={"transactions-completed-header d-none d-md-block"}>
              Completed
            </div>
            <div>{groups.completed}</div>
          </>
        )}

        {groups.pending.length > 0 && (
          <>
            <div className={"transactions-completed-header d-none d-md-block"}>
              Pending
            </div>
            <div>{groups.pending}</div>
          </>
        )}

        {groups.cancelled.length > 0 && (
          <>
            <div className={"transactions-completed-header d-none d-md-block"}>
              Cancelled
            </div>
            <div>{groups.cancelled}</div>
          </>
        )}
      </div>
    );
  }

  return (
    <div className="flex-dir-column d-flex gap-4 gap-md-0">
      {getTransactionsTemplates()}
    </div>
  );
};
