import React from "react";
import moment from "moment";
import { Log } from "@modules/account/ts/types/order/order-view.types";
import classes from "./OrderLog.module.scss";
interface LogItem {
  item: Log;
}
export const LogItem: React.FC<LogItem> = ({ item }) => {
  const getClassByTypeLog = (type: string) => {
    switch (type) {
      case "C":
        return classes.customerService;
      case "U":
        return classes.user;
      default:
        return "";
    }
  };
  return (
    <div className={`${classes.orderLogItem} ${getClassByTypeLog(item.type)}`}>
      <div className={classes.cell}>
        {moment.unix(item.date).format("D-MMM-YYYY h:mm:ss")}
      </div>
      <div className={classes.cell}>{item.name}</div>
      <div className={classes.cell}>{item.type}</div>
      <div
        className={classes.cell}
        dangerouslySetInnerHTML={{ __html: item.action }}
      />
    </div>
  );
};
