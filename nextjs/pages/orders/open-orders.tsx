import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import OrdersPage from "@modules/account/pages/OrdersPage";

function OpenOrders(props: Record<any, any>) {
  return (
    <PageTwoColumns>
      <OrdersPage label={"Open orders"} type={"open"} />
    </PageTwoColumns>
  );
}

export default OpenOrders;
