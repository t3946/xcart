import React from "react";
import { CardItem } from "./CardItem";

export const CardsList = ({ cards }) => {
  return (
    <div className="wallet-cards-list-container">
      {cards.map((e, index) => {
        return <CardItem cardInfo={e} firstChild={index === 0} />;
      })}
    </div>
  );
};
