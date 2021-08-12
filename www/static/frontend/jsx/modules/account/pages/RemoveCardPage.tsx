import React from "react";
import { RemoveCard } from "../components/wallet/RemoveCard";
import { useLocation } from "react-router-dom";
import { CardItemDto } from "../ts/types/wallet.type";

interface LocationCardState {
  cardInfo: CardItemDto;
}

export const RemoveCardPage: React.FC = () => {
  const location = useLocation<LocationCardState>();
  return <RemoveCard cardInfo={location.state.cardInfo} />;
};
