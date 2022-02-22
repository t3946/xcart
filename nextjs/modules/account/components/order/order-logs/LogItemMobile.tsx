import React from "react";
import cn from "classnames";
import { Log } from "@modules/account/ts/types/order/order-view.types";
import moment from "moment";
import ToastArrow from "@modules/icon/components/account/toast-arrow/ToastArrow";

import Styles from "@modules/account/components/order/order-logs/LogItemMobile.module.scss";

interface IProps {
  item: Log;
}

const LogItemMobile: React.FC<IProps> = ({ item }) => {
  return (
    <div>
      <div>
        <div
          className={Styles.message}
          dangerouslySetInnerHTML={{ __html: item.action }}
        />
        <ToastArrow className={Styles.messageArrow} />
      </div>
      <div className={cn(Styles.details, "mt-14")}>
        by {item.name} <i>({item.type})</i> on{" "}
        {moment.unix(item.date).utc().format("MMM D, YYYY")}
      </div>
    </div>
  );
};

export default LogItemMobile;
