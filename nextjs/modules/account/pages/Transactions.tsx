import React from "react";
import { TransactionsList } from "../components/wallet-transactions/TransactionsList";
import Button from "@modules/ui/forms/Button";

interface IProps {
  orders: Record<any, any>[];
  cards: Record<any, any>[];
}

export const Transactions: React.FC<IProps> = (props) => {
  const { orders, cards } = props;

  function isEmpty() {
    for (const order of orders) {
      if (order.xcart_order_transactions.length === 0) {
        continue;
      } else {
        return false;
      }
    }

    return true;
  }
  return (
    <div>
      <div className="page-label">Transactions</div>
      {isEmpty() ? (
        <div className="d-flex">
          <div className="mx-auto">
            <p>You don’t have any transactions yet </p>
            <a className="text-decoration-none" href={"/"}>
              <Button className={"w-auto"}>Continue shopping</Button>
            </a>
          </div>
        </div>
      ) : (
        <>
          <div className="wallet-label fw-normal fs-6">
            Refer below for your most recent transactions.
          </div>

          <TransactionsList orders={orders} cards={cards} />
        </>
      )}
    </div>
  );
};
