import React, { useEffect } from "react";
import { CardsList } from "../components/wallet/CardsList";
import { AddNewPaymentMethod } from "../components/wallet/AddNewPaymentMethod";
import { useDispatch, useSelector } from "react-redux";
import { getCards } from "../../../redux/actions/account-actions/PaymentsActions";
import { AccountStore } from "../ts/types/account-store.type";

export const Wallet: React.FC = () => {
  const dispatch = useDispatch();
  const cards = useSelector((e: AccountStore) => e.payments.cards);

  useEffect(() => {
    if (!cards) {
      dispatch(getCards());
    }
  }, []);

  return (
    <div className="wallet-container">
      <div className="page-label">Wallet</div>
      {cards?.length > 0 && (
        <div className="wallet-label">Credit and debit cards</div>
      )}
      <CardsList cards={cards} />
      <div className="wallet-label">Add a new payment method</div>
      <AddNewPaymentMethod />
    </div>
  );
};
