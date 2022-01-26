import React from "react";
import Navigation from "@modules/account/components/orders/Navigation/Navigation";

interface IProps {
  orderNumber: string;
  orderId: number;
}

export const OrderInfoHeader: React.FC<IProps> = (props: IProps) => {
  const { orderNumber, orderId } = props;

  return (
    <>
      <div className={"order-info-header-title"}>Order # {orderNumber}</div>
      <Navigation orderId={orderId} />
    </>
  );
};
