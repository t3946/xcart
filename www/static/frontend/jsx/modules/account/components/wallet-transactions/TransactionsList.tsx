import React from "react";
import { TransactionItem } from "./TransactionItem";

export const TransactionsList = () => {
  const mass = [1, 2, 3, 4, 5, 6];
  return (
    <div>
      <div className={"transactions-completed-header"}>Completed</div>

      {mass.map((e) => {
        return <TransactionItem />;
      })}
    </div>
  );
};
