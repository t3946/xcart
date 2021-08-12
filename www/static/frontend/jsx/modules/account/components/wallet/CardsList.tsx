import React from "react";
import { CardItem } from "./CardItem";
import { useDispatch, useSelector } from "react-redux";
import { LoadingContainer } from "../shared/LoadingContainer";
import { AccountStore } from "../../ts/types/account-store.type";
import { changeDefaultCard } from "@client/jsx/redux/actions/account-actions/WalletActions";
import { CardItemDto } from "@client/modules/account/ts/types/wallet.type";
import { useHistory } from "react-router";

interface CardsListProps {
  cards: CardItemDto[];
}

export const CardsList: React.FC<CardsListProps> = ({ cards }) => {
  const breakPoint = useSelector((e: any) => e.main.breakpoint);

  const submitCardFormLoading = useSelector(
    (e: AccountStore) => e.wallet.submitCardFormLoading
  );

  const dispatch = useDispatch();

  const history = useHistory();

  const changeDefault = (cardInfo: CardItemDto, e) => {
    e.stopPropagation();
    if (!cardInfo.is_default) {
      dispatch(changeDefaultCard(cardInfo.credit_card_id));
    }
  };

  const openCardDialog = (cardInfo: CardItemDto, dialog, path) => {
    if (breakPoint.is768) {
      history.push({
        pathname: path,
        state: { cardInfo: cardInfo },
      });
      return;
    }
    dialog.handleClickOpen();
  };
  return (
    <div className="wallet-cards-list-container">
      {cards?.map((e) => {
        return (
          <LoadingContainer loading={submitCardFormLoading}>
            <CardItem
              changeDefault={changeDefault}
              openCardDialog={openCardDialog}
              breakPoint={breakPoint}
              cardInfo={e}
            />
          </LoadingContainer>
        );
      })}
    </div>
  );
};
