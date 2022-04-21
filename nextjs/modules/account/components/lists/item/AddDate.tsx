import * as React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/lists/item/AddDate.module.scss";
import moment from "moment";

interface IProps {
  date: string;
  className: any;
}

export const AddDate: React.FC<IProps> = function (props) {
  const { date, className } = props;
  return (
    <div className={cn(Styles.productInfoDate, className)}>
      Item added {moment(date).utc().format("MMM DD, Y")}
    </div>
  );
};

export default AddDate;
