import React from "react";
import classnames from "classnames";
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
    <div className={classnames(`col-4 ps-0`, containerClass)}>
      <div>
        <PaymentCardImage logo={cardIconPath} name={cardType} /> ending in{" "}
        {cardLast4}
      </div>
    </div>
  );
};
