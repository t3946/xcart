import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import OrdersPage from "@modules/account/pages/OrdersPage";

function CompletedOrders() {
  return (
    <PageTwoColumns>
      <OrdersPage label={"Completed orders"} type={"completed"} />
    </PageTwoColumns>
  );
}

export default CompletedOrders;
