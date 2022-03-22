import React from "react";
import { TransactionItem } from "./TransactionItem";
import { TransactionItemRefund } from "./TransactionItemRefund";

function getGroup(transaction: any): string | null {
  switch (transaction.type) {
    case "authorization":
      switch (transaction.transaction_status) {
        case "declined":
        case "failed":
        case "expired":
          return "failed";
        case "captured":
        case "partially_captured":
          return "settled";
        case "voided":
          return "cancelled";
        case "authorized":
          return "pending";
        case "pending":
          return null;
      }
      break;
    case "capture":
      switch (transaction.transaction_status) {
        case "completed":
          return "settled";
        case "refunded":
          return "refunded";
        case "partially_refunded":
          return "partially_refunded";
      }
      break;
    case "refund":
      switch (transaction.transaction_status) {
        case "completed":
        case "refunded":
          return "refund";
      }
      break;
  }
  return null;
}

interface IProps {
  orders: Record<any, any>[];
  cards: Record<any, any>[];
}

export const TransactionsList: React.FC<IProps> = (props) => {
  const { orders } = props;

  function getTransactionsTemplates() {
    const groups: any = {
      refund: [],
      refunded: [],
      partially_refunded: [],
      settled: [],
      pending: [],
      cancelled: [],
      failed: [],
    };

    const groupTitles: any = {
      refund: "Refund",
      refunded: "Fully refunded",
      partially_refunded: "Partially refunded",
      settled: "Settled",
      pending: "Pending",
      cancelled: "Canceled",
      failed: "Failed",
    };

    for (const order of orders) {
      for (const transaction of order.xcart_order_transactions) {
        //todo: TransactionItemRefund
        const Item =
          transaction.type === "refund"
            ? TransactionItemRefund
            : TransactionItem;
        const card = null;
        const group = getGroup(transaction);

        if (group !== null) {
          groups[group].push(
            <Item
              header={groupTitles[group]}
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
        {groups.settled.length > 0 && (
          <>
            <div className={"transactions-completed-header d-none d-md-block"}>
              Settled
            </div>
            <div>{groups.settled}</div>
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

        {groups.partially_refunded.length > 0 && (
          <>
            <div className={"transactions-completed-header d-none d-md-block"}>
              Partially refunded
            </div>
            <div>{groups.partially_refunded}</div>
          </>
        )}

        {groups.refunded.length > 0 && (
          <>
            <div className={"transactions-completed-header d-none d-md-block"}>
              Fully refunded
            </div>
            <div>{groups.refunded}</div>
          </>
        )}

        {groups.refund.length > 0 && (
          <>
            <div className={"transactions-completed-header d-none d-md-block"}>
              Refund
            </div>
            <div>{groups.refund}</div>
          </>
        )}

        {groups.failed.length > 0 && (
          <>
            <div className={"transactions-completed-header d-none d-md-block"}>
              Failed
            </div>
            <div>{groups.failed}</div>
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
