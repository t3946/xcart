import React from "react";
import { CardItem } from "./CardItem";
import { useDispatch, useSelector } from "react-redux";
import { LoadingContainer } from "../shared/LoadingContainer";
import StoreInterface from "@modules/account/ts/types/store.type";
import { changeDefaultCard } from "@redux/actions/account-actions/PaymentsActions";
import { CardItemDto } from "@modules/account/ts/types/wallet.type";
import { useHistory } from "react-router";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";

interface CardsListProps {
  cards: CardItemDto[];
}

export const CardsList: React.FC<CardsListProps> = ({ cards }) => {
  const breakpoint = useBreakpoint();

  const submitCardFormLoading = useSelector(
    (e: StoreInterface) => e.payments.submitCardFormLoading
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
    breakpoint({
      sm: () =>
        history.push({
          pathname: path,
          state: { cardInfo: cardInfo },
        }),
      md: dialog.handleClickOpen,
    });
  };
  return (
    <div className="wallet-cards-list-container">
      {cards?.map((e) => {
        return (
          <LoadingContainer loading={submitCardFormLoading}>
            <CardItem
              changeDefault={changeDefault}
              openCardDialog={openCardDialog}
              cardInfo={e}
            />
          </LoadingContainer>
        );
      })}
    </div>
  );
};
