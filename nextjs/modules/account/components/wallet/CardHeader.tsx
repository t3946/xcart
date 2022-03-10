import React from "react";
import cn from "classnames";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import PaymentCardImage from "@components/common/payment-card-image/PaymentCardImage";

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
  const cardIconPath = useSelectorAccount(
    (e) => e.payments.methods.find((method) => method.name === cardType)?.logo
  );

  return (
    <div className={cn(containerClass)}>
      <div className="d-flex align-items-center">
        <PaymentCardImage logo={cardIconPath} name={cardType} />
        <div className={"ms-4"}>
          <b className="d-block d-lg-none">{cardType.toUpperCase()}</b>
          <span className="d-none d-lg-inline-block">{cardType}</span>{" "}
          <span className="d-inline-block">ending in {cardLast4}</span>
        </div>
      </div>
    </div>
  );
};
