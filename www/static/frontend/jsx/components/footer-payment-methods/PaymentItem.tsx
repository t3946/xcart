import * as React from "react";
import Chevron from "@client/jsx/components/footer-payment-methods/icons/chevron/Chevron";
import cn from "classnames";
import Styles from "@client/jsx/components/footer-payment-methods/PaymentItem.module.scss";

interface IProps {
  paymentMethod: Record<any, any>;
  isMultiple?: boolean;
  isOpen?: boolean;
  ref?: any;
}

export const PaymentItem: React.FC<IProps> = function (props) {
  const {paymentMethod, isMultiple = false, isOpen = false, ref} = props;

  return (
    <div className={cn("position-relative", {[Styles.item_multiple]: isMultiple})} ref={ref}>
      <img width="54" height="36" className="lazy-img footer-payment-method-image"
           src={"/" + paymentMethod.logo} alt={paymentMethod.name}
      />

      {isMultiple && <Chevron className={cn([Styles.arrow, Styles.item__arrow, {
        [Styles.arrow_active]: isOpen
      }])}/>}
    </div>
  );
};

export default PaymentItem;
