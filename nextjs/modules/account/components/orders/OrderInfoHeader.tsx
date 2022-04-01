import React from "react";
import Navigation from "@modules/account/components/orders/Navigation/Navigation";

interface IProps {
  order: string;
}

export const OrderInfoHeader: React.FC<IProps> = (props: IProps) => {
  const { order } = props;
  const orderNumber = order.order_prefix + order.orderid;

  return (
    <>
      <div className={"order-info-header-title"}>Order # {orderNumber}</div>
      <Navigation orderId={order.orderid} orderStatus={order.cb_status} />
    </>
  );
};
