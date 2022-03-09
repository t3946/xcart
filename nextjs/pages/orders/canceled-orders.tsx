import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import OrdersPage from "@modules/account/pages/OrdersPage";

function CanceledOrders() {
  return (
    <PageTwoColumns>
      <OrdersPage label={"Cancelled orders"} type={"cancelled"} />
    </PageTwoColumns>
  );
}

export default CanceledOrders;
