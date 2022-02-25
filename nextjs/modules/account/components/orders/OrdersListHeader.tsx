import React from "react";
import Select from "@modules/ui/forms/select/Select";
import { ordersHeaderSelectValues } from "@modules/account/ts/consts/orders-header-select-values";
import { SelectValue } from "@modules/account/ts/types/select-value.type";
import { useDispatch } from "react-redux";
import { changeTimeGap } from "@redux/actions/account-actions/OrdersActions";
import Styles from "@modules/account/components/orders/OrdersListHeader.module.scss";
import cn from "classnames";

interface OrdersListHeaderProps {
  label: string;
  selectValue: SelectValue<number, string>;
}

export const OrdersListHeader: React.FC<OrdersListHeaderProps> = ({
  label,
  selectValue,
}) => {
  const dispatch = useDispatch();
  const onSelectValueChange = (value) => {
    dispatch(changeTimeGap(value));
  };
  return (
    <div className="orders-list-header">
      <div className={"page-label"}>{label}</div>
      <div className={"d-flex align-items-center"}>
        <div className={cn(Styles.timePeriod, "me-2")}>Time period:</div>
        <Select
          classes={{ indicatorSeparator: "d-none" }}
          clearable={false}
          name={"awd"}
          options={ordersHeaderSelectValues}
          value={selectValue}
          onChange={(e) => {
            onSelectValueChange(e.target.value);
          }}
        />
      </div>
    </div>
  );
};
