import React, { useEffect } from "react";
import { CardsList } from "../components/wallet/CardsList";
import { AddNewPaymentMethod } from "../components/wallet/AddNewPaymentMethod";
import { useDispatch } from "react-redux";
import { getCards } from "@redux/actions/account-actions/PaymentsActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { CardItemDto } from "@modules/account/ts/types/wallet.type";
import InnerPage from "@components/common/inner-page/InnerPage";
import GreyGrid from "@components/common/grey-grid/GreyGrid";

export const Wallet: React.FC = () => {
  const dispatch = useDispatch();
  const cards: CardItemDto[] = useSelectorAccount(
    (store) => store.payments.cards
  );

  useEffect(() => {
    if (!cards) {
      dispatch(getCards());
    }
  }, []);

  return (
    <>
      <InnerPage header={"Wallet"}>
        {cards?.length > 0 && <h2>Credit and debit cards</h2>}
        <CardsList cards={cards} />
        <h2>Add a new payment method</h2>
      </InnerPage>

      <GreyGrid>
        <AddNewPaymentMethod />
      </GreyGrid>
    </>
  );
};
