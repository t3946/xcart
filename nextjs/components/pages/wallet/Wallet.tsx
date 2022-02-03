import React from "react";
import { AddNewPaymentMethod } from "@modules/account/components/wallet/AddNewPaymentMethod";
//todo: deprecated
// import { getCards } from "@redux/actions/account-actions/PaymentsActions";
import InnerPage from "@components/common/inner-page/InnerPage";
import GreyGrid from "@components/common/grey-grid/GreyGrid";
import { Card as ICard } from "@stripe/stripe-js";
import Card from "@components/pages/wallet/Card";
import { changeDefaultCard } from "@redux/actions/account-actions/PaymentsActions";
import { useDispatch } from "react-redux";
import { AxiosResponse } from "axios";
import { CardItemDto } from "@modules/account/ts/types/wallet.type";

interface IProps {
  cards: ICard[];
  defaultCardId: string;
}

export const Wallet: React.FC<IProps> = (props) => {
  const dispatch = useDispatch();
  const { cards } = props;
  const [defaultCardId, setDefaultCardId] = React.useState(props.defaultCardId);

  function cardItemsTemplate() {
    const items = [];

    const openCardDialog = (cardInfo: CardItemDto, dialog, path) => {
      dialog.handleClickOpen;
    };

    for (const card of cards) {
      items.push(
        <Card
          key={`card-${card.id}`}
          card={card}
          isDefault={card.id === defaultCardId}
          changeDefaultCardId={changeDefaultCardId}
          openCardDialog={openCardDialog}
        />
      );
    }

    return items;
  }

  function cardsList() {
    return (
      <div className="wallet-cards-list-container">
        <h2>Credit and debit cards</h2>
        {cardItemsTemplate()}
      </div>
    );
  }

  function changeDefaultCardId(newDefaultCardId: string) {
    dispatch(
      changeDefaultCard({
        data: {
          source: newDefaultCardId,
        },
        success(res: AxiosResponse) {
          setDefaultCardId(res.data.customer.default_source);
        },
      })
    );
  }

  return (
    <>
      <InnerPage header={"Wallet"}>
        {cards?.length > 0 && cardsList()}

        <h2>Add a new payment method</h2>
      </InnerPage>

      <GreyGrid>
        <AddNewPaymentMethod />
      </GreyGrid>
    </>
  );
};
