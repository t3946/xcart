import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import { NextPage } from "next";
import { OrderInfoHeader } from "@modules/account/components/orders/OrderInfoHeader";
import { useEffect } from "react";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";
import { setOrderView } from "@redux/actions/account-actions/OrdersActions";
import { OrderTrackingPage } from "@modules/account/pages/OrderTrackingPage";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { OrderLogPage } from "@modules/account/pages/OrderLogPage";
import { OrderAddressesPage } from "@modules/account/pages/OrderAddressesPage";
import { ProductsOrderedPage } from "@modules/account/pages/ProductsOrderedPage";
import { OrderActionsPage } from "@modules/account/pages/OrderActionsPage";

const OrderPage: NextPage = () => {
  const router = useRouter();
  const dispatch = useDispatch();
  const orderId = Number(router.query.id);
  const order: OrderView = useSelectorAccount((store) => store.orderView);
  useEffect(() => {
    dispatch(setOrderView(Number(orderId)));
  }, []);
  const getSection = () => {
    switch (router.query.type) {
      case "order-tracking":
        return <OrderTrackingPage />;
      case "log":
        return <OrderLogPage logs={order.logs} />;
      case "products-ordered":
        return <ProductsOrderedPage orderItem={order} />;
      case "addresses":
        return <OrderAddressesPage orderItem={order} />;
      // case "communication":
      //   return <OrderCommunicationPage orderItem={order} />;
      case "order-actions":
        return <OrderActionsPage orderItem={order} />;
      case "decisions-required":
        return null; // TODO: Сделать decisions page
    }
  };
  return (
    <PageTwoColumns>
      <OrderInfoHeader orderId={orderId} />
      {order && getSection()}
    </PageTwoColumns>
  );
};
export default OrderPage;
