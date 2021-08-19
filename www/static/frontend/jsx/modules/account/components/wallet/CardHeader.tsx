import React from "react";
import classnames from "classnames";

interface CardHeaderProps {
  cardNumber: string;
  cardType: string;
  containerClass?: string | string[];
}

export const CardHeader: React.FC<CardHeaderProps> = ({
  cardNumber,
  cardType,
  containerClass,
}) => {
  return (
    <div
      className={classnames(
        `wallet-card-name wallet-card-name-header`,
        containerClass
      )}
    >
      <img
        className="wallet-card-img"
        src={`/static/frontend/dist/images/icons/account/cards/${cardType}.svg`}
      />
      <div>
        <b>{cardType[0].toUpperCase() + cardType.slice(1)}</b> ending in
        {` ${cardNumber.substr(cardNumber.length - 4)}`}
      </div>
    </div>
  );
};
