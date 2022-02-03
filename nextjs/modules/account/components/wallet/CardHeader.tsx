import React from "react";
import classnames from "classnames";

interface CardHeaderProps {
  cardLast4: string;
  cardType: string;
  containerClass?: string | string[];
}

export const CardHeader: React.FC<CardHeaderProps> = ({
  cardLast4,
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
      <div>
        <b>{cardType.toUpperCase()}</b> ending in{" "}
        {cardLast4}
      </div>
    </div>
  );
};
