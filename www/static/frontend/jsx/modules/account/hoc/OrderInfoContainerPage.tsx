import React, { JSXElementConstructor, ReactElement } from "react";
import { OrderInfoHeader } from "@client/modules/account/components/orders/OrderInfoHeader";
import { OrderPageURLParams } from "@client/modules/account/ts/types/order-page-url-params.type";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { useSelector } from "react-redux";
import { useParams } from "react-router-dom";

export const OrderInfoContainerPage: React.FC = ({ children }) => {
  const urlParams = useParams<OrderPageURLParams>();

  console.log(urlParams.orderType);

  const orderItem = useSelector((e: AccountStore) =>
    e.ordersStore.orders[urlParams.orderType].items.find(
      (e) => e.orderInfo.orderid === urlParams.id
    )
  );
  return (
    <div>
      <OrderInfoHeader orderItem={orderItem} />
      {React.Children.map(children, (child: ReactElement<{ orderItem: any }>) =>
        React.cloneElement(child, { orderItem })
      )}
    </div>
  );
};
