import React from "react";
import { CardItem } from "./CardItem";
import { useBreakPoint } from "../../hooks/useBreakPoint";

export const CardsList = ({ cards }) => {
  const breakPoint = useBreakPoint();
  return (
    <div className="wallet-cards-list-container">
      {cards.map((e, index) => {
        return (
          <CardItem
            breakPoint={breakPoint}
            cardInfo={e}
            firstChild={index === 0}
          />
        );
      })}
    </div>
  );
};
