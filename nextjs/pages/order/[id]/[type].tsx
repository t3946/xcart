import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import {NextPage} from "next";
import {OrderInfoHeader} from "@modules/account/components/orders/OrderInfoHeader";
import {OrderTrackingPage} from "@modules/account/pages/OrderTrackingPage";
import {OrderLogPage} from "@modules/account/pages/OrderLogPage";
import {OrderAddressesPage} from "@modules/account/pages/OrderAddressesPage";
import {ProductsOrderedPage} from "@modules/account/pages/ProductsOrderedPage";
import {OrderActionsPage} from "@modules/account/pages/OrderActionsPage";
import {setOrderView} from "@redux/actions/account-actions/OrdersActions";
import {OrderView} from "@modules/account/ts/types/order/order-view.types";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import {useDispatch} from "react-redux";
import {useRouter} from "next/router";
import React, {useEffect} from "react";
import Decision from "@modules/account/components/orders/Decision/Decision";

const OrderPage: NextPage = () => {
  const dispatch = useDispatch();
  const router = useRouter();
  const { type, id } = router.query;
  useEffect(() => {
    dispatch(setOrderView(Number(id)));
  }, []);

  const order: OrderView = useSelectorAccount((state) => state.orderView);

  if (!order) {
    return null;
  }
  const getSection = () => {
    switch (type) {
      case "order-tracking":
        return <OrderTrackingPage order={order} />;
      case "log":
        return <OrderLogPage logs={order.logs} />;
      case "products-ordered":
        return <ProductsOrderedPage order={order} />;
      case "addresses":
        return <OrderAddressesPage orderItem={order} />;
      // case "communication":
      //   return <OrderCommunicationPage orderItem={order} />;
      case "order-actions":
        return <OrderActionsPage orderItem={order} />;
      case "decisions-required":
        // return null; // TODO: Сделать decisions page
        return <Decision decision={order} />;
    }
  };

  return (
    <PageTwoColumns>
      <OrderInfoHeader
        orderNumber={order.orderNumber}
        orderId={order.orderId}
        orderStatus={order.cb_status}
      />

      {getSection()}
    </PageTwoColumns>
  );
};
export default OrderPage;
