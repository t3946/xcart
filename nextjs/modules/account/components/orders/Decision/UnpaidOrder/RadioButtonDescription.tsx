import React from "react";
import paymentItemStyles from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentItem.module.scss";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/RadioButtonDescription.module.scss";
import cn from "classnames";

interface IProps {
  integrated?: boolean;
  children: React.ReactNode;
}

const RadioButtonDescription: React.FC<IProps> = (props: IProps) => {
  const { integrated, children } = props;
  return (
    <div
      className={cn([
        paymentItemStyles.paymentItemGrid,
        Styles.paymentItemDetails,
        { [paymentItemStyles.paymentItemGrid_integrated]: integrated },
      ])}
    >
      <div className={Styles.paymentItemDetails__content}>{children}</div>
    </div>
  );
};

export default RadioButtonDescription;
