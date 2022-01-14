import React, { useEffect } from "react";
import { CardsList } from "../components/wallet/CardsList";
import { AddNewPaymentMethod } from "../components/wallet/AddNewPaymentMethod";
import { useDispatch, useSelector } from "react-redux";
import { getCards } from "../../../redux/actions/account-actions/PaymentsActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import {CardItemDto} from "@modules/account/ts/types/wallet.type";

export const Wallet: React.FC = () => {
  const dispatch = useDispatch();
  const cards: CardItemDto[] = useSelectorAccount(store => store.payments.cards);

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
