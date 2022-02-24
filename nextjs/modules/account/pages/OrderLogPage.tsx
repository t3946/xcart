import React from "react";
import { Log } from "@modules/account/ts/types/order/order-view.types";
import OrderLog from "@modules/account/components/order/order-logs/OrderLog";
import NoItems from "@modules/account/components/common/NoItems";

interface OrderLogPage {
  logs: Log[];
}

export const OrderLogPage: React.FC<OrderLogPage> = ({ logs }) => {
  return (
    <div>
      <div className="page-label">Order log</div>
      {logs.length > 0 ? (
        <OrderLog logs={logs} />
      ) : (
        <NoItems message={"No logs for this order"} />
      )}
    </div>
  );
};
