import React from "react";
import FormSelect from "@modules/ui/forms/Select";
import { ordersHeaderSelectValues } from "@modules/account/ts/consts/orders-header-select-values";
import { SelectValue } from "@modules/account/ts/types/select-value.type";
import { useDispatch } from "react-redux";
import { changeTimeGap } from "@redux/actions/account-actions/OrdersActions";

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
        <div>Time period:</div>
        <FormSelect
          classes={{
            group: "orders-list-header-select-group",
            selectHeader: "orders-list-header-select-header",
            input: "orders-list-header-select-input",
          }}
          value={selectValue}
          onClick={onSelectValueChange}
          items={ordersHeaderSelectValues}
          id="orders-select"
          name={"awd"}
        />
      </div>
    </div>
  );
};
