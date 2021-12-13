import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import OrdersPage from "@modules/account/pages/OrdersPage";

function CompletedOrders() {
  return (
    <PageTwoColumns>
      <OrdersPage label={"Cancelled orders"} type={"completed"} />
    </PageTwoColumns>
  );
}

export default CompletedOrders;
