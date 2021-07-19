import React from "react";
import { CardsList } from "../components/wallet/CardsList";
import { AddNewPaymentMethod } from "../components/wallet/AddNewPaymentMethod";

export const Wallet = () => {
  return (
    <div className="wallet-container">
      <div className="page-label">Wallet</div>
      <div className="wallet-label">Credit and debit cards</div>
      <CardsList />
      <div className="wallet-label">Add a new payment method</div>
      <AddNewPaymentMethod />
    </div>
  );
};
