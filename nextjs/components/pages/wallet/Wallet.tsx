import React from "react";
import { AddNewPaymentMethod } from "@components/pages/wallet/AddNewPaymentMethod";
//todo: deprecated
// import { getCards } from "@redux/actions/account-actions/PaymentsActions";
import StylesInnerPage from "@components/common/inner-page/InnerPage.module.scss";
import GreyGrid from "@components/common/grey-grid/GreyGrid";
import { Card as ICard } from "@stripe/stripe-js";
import Card from "@components/pages/wallet/Card";
import { changeDefaultCard } from "@redux/actions/account-actions/PaymentsActions";
import { useDispatch } from "react-redux";
import { AxiosResponse } from "axios";
import { CardItemDto } from "@modules/account/ts/types/wallet.type";
import cn from "classnames";
import EditPaymentMethod from "@components/pages/wallet/dialog/EditPaymentMethod";

interface IProps {
  cards: ICard[];
  defaultCardId: string;
}

export const Wallet: React.FC<IProps> = (props) => {
  const dispatch = useDispatch();
  const { cards } = props;
  const [defaultCardId, setDefaultCardId] = React.useState(props.defaultCardId);
  const [editCard, setEditCard] = React.useState<ICard | null>(null);

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
          editCard={setEditCard}
        />
      );
    }

    return items;
  }

  function cardsList() {
    return (
      <div className="wallet-cards-list-container">
        <h2 className={cn(StylesInnerPage.accountPageContainer, "mb-0")}>
          Credit and debit cards
        </h2>
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
      <h1
        className={cn(
          StylesInnerPage.accountPageHat,
          StylesInnerPage.pageHeader,
          "mb-0"
        )}
      >
        Wallet
      </h1>

      {cards?.length > 0 && cardsList()}

      <h2 className={cn(StylesInnerPage.accountPageContainer)}>
        Add a new payment method
      </h2>

      <GreyGrid>
        <AddNewPaymentMethod />
      </GreyGrid>

      <EditPaymentMethod
        open={!!editCard}
        card={editCard}
        handleClose={() => {
          setEditCard(null);
        }}
      />
    </>
  );
};
