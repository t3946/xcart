import React from "react";
import Navigation from "@modules/account/components/orders/Navigation/Navigation";

interface IProps {
  orderNumber: string;
  orderId: number;
  orderStatus: string;
}

export const OrderInfoHeader: React.FC<IProps> = (props: IProps) => {
  const { orderNumber, orderId, orderStatus } = props;

  return (
    <>
      <div className={"order-info-header-title"}>Order # {orderNumber}</div>
      <Navigation orderId={orderId} orderStatus={orderStatus} />
    </>
  );
};
