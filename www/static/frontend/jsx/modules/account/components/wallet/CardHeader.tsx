import React from "react";
import classnames from "classnames";

export const CardHeader = ({ cardNumber, cardType, containerClass = null }) => {
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
        {cardType[0].toUpperCase() + cardType.slice(1)} ending in{" "}
        {cardNumber.substr(cardNumber.length - 4)}
      </div>
    </div>
  );
};
