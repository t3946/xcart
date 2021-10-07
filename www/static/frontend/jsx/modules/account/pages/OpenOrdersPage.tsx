import React from "react";
import { OrdersList } from "@client/modules/account/components/orders/OrdersList";

export const OpenOrdersPage: React.FC = () => {
  return (
    <div>
      <OrdersList label="Open orders" type="open" />
    </div>
  );
};
