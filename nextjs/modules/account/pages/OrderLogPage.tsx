import React, { Fragment } from "react";
import { Log } from "@modules/account/ts/types/order/order-view.types";
import moment from "moment";
import { LogItem } from "@modules/account/components/order/order-logs/LogItem";
import { LogHeader } from "@modules/account/components/order/order-logs/LogHeader";

interface OrderLogPage {
  logs: Log[];
}

export const OrderLogPage: React.FC<OrderLogPage> = ({ logs }) => {
  return (
    <div>
      <div className="page-label">Order log</div>
      <LogHeader />
      {logs.map((item) => (
        <LogItem item={item} key={item.id} />
      ))}
    </div>
  );
};
