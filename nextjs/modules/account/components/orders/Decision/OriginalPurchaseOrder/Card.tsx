import React from "react";
import Styles from "@modules/account/components/orders/Decision/OriginalPurchaseOrder/OriginalPurchaseOrder.module.scss";
import cn from "classnames";

const Card: React.FC = ({ children }) => {
  return (
    <div
      className={cn([
        // "d-flex",
        // "flex-dir-column",
        Styles.card,
        Styles.decisionCard,
      ])}
    >
      {children}
    </div>
  );
};

export default Card;
