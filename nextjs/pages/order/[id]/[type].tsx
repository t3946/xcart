import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import { NextPage, NextPageContext } from "next";
import { OrderInfoHeader } from "@modules/account/components/orders/OrderInfoHeader";
import { OrderTrackingPage } from "@modules/account/pages/OrderTrackingPage";
import { OrderLogPage } from "@modules/account/pages/OrderLogPage";
import { OrderAddressesPage } from "@modules/account/pages/OrderAddressesPage";
import { ProductsOrderedPage } from "@modules/account/pages/ProductsOrderedPage";
import { OrderActionsPage } from "@modules/account/pages/OrderActionsPage";
import { getInstance } from "@services/axios/Instance";

export async function getServerSideProps(ctx: NextPageContext) {
  const instance = getInstance(ctx.req);
  const { id: orderId, type } = ctx.query;
  let order;

  await instance.get(`/api/account/orders/get-one/${orderId}`).then((res) => {
    order = res.data;
  });

  return {
    props: { order, type },
  };
}

interface IProps {
  order: Record<any, any>;
  type: string;
}

const OrderPage: NextPage<IProps> = (props: IProps) => {
  const { order, type } = props;

  const getSection = () => {
    switch (type) {
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
      <OrderInfoHeader
        orderNumber={order.orderNumber}
        orderId={order.orderId}
      />

      {order && getSection()}
    </PageTwoColumns>
  );
};
export default OrderPage;
