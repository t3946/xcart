import React from "react";
import { Log } from "@modules/account/ts/types/order/order-view.types";
import { LogItem } from "@modules/account/components/order/order-logs/LogItem";
import { LogHeader } from "@modules/account/components/order/order-logs/LogHeader";
import LogItemMobile from "@modules/account/components/order/order-logs/LogItemMobile";

interface IProps {
  logs: Log[];
}

const OrderLog: React.FC<IProps> = ({ logs }) => {
  return (
    <>
      <div className="d-none d-md-block">
        <LogHeader />
        {logs.map((item) => (
          <LogItem item={item} key={item.id} />
        ))}
      </div>

      <div className="d-md-none">
        {logs.map((item) => (
          <LogItemMobile item={item} key={item.id} />
        ))}
      </div>
    </>
  );
};

export default OrderLog;
