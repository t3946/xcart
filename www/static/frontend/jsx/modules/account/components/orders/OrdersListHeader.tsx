import React from "react";
import { FormSelect } from "@client/modules/account/components/shared/FormSelect";
import { ordersHeaderSelectValues } from "@client/modules/account/ts/consts/orders-header-select-values";
import { SelectValue } from "@client/modules/account/ts/types/select-value.type";
import { useDispatch } from "react-redux";
import { changeTimeGap } from "@client/jsx/redux/actions/account-actions/OrdersActions";

interface OrdersListHeaderProps {
  label: string;
  selectValue: SelectValue<number, string>;
  orderType: string;
}

export const OrdersListHeader: React.FC<OrdersListHeaderProps> = ({
  label,
  selectValue,
  orderType,
}) => {
  const dispatch = useDispatch();
  const onSelectValueChange = (value) => {
    dispatch(changeTimeGap(orderType, value));
  };
  return (
    <div className="orders-list-header">
      <div className={"page-label"}>{label}</div>
      <div className={"d-flex align-items-center"}>
        <div>Time period:</div>
        <FormSelect
          classes={{
            group: "orders-list-header-select-group",
            selectHeader: "orders-list-header-select-header",
          }}
          value={selectValue}
          onClick={onSelectValueChange}
          items={ordersHeaderSelectValues}
          id="orders-select"
        />
      </div>
    </div>
  );
};
