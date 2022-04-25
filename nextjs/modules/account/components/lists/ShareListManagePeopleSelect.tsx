import React from "react";
import cn from "classnames";
import ReactSelect, { components } from "react-select";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";
import { SelectValue } from "@modules/account/ts/types/select-value.type";
import SelectCheck from "@modules/icon/components/account/check/SelectCheck";
import Input from "@modules/ui/forms/select/Input";
import MenuList from "@modules/ui/forms/select/MenuList";
import IndicatorsContainer from "@modules/ui/forms/select/IndicatorsContainer";
import DropdownIndicator from "@modules/ui/forms/select/DropdownIndicator";
import IndicatorSeparator from "@modules/ui/forms/select/IndicatorSeparator";
import { roleDelete } from "@redux/actions/account-actions/ListsActions";

import StylesOption from "@modules/ui/forms/select/Option.module.scss";
import StylesControl from "@modules/ui/forms/select/Control.module.scss";
import Styles from "@modules/account/components/lists/ShareListManagePeopleSelect.module.scss";
import { useDispatch } from "react-redux";

interface IProps {
  items: SelectValue<UserRightsActionsEnum, string>[];
  onClick: (value: SelectValue<UserRightsActionsEnum, string>) => void;
  value: SelectValue<UserRightsActionsEnum, string>;
  name: string;
  user: any;
  list: any;
}

export const ShareListManagePeopleSelect: React.FC<IProps> = (props) => {
  const { items, onClick, value, name, user, list } = props;

  return (
    <ReactSelect
      instanceId={"select-role"}
      user={user}
      list={list}
      classes={{
        indicatorSeparator: "d-none",
        control: ["border-0", "cursor-pointer"],
        option: [Styles.option, "ps-3"],
        menu: "mt-0",
      }}
      isClearable={false}
      isSearchable={false}
      onChange={(newValue) => {
        value !== newValue && onClick && onClick(newValue);
      }}
      value={value}
      name={name}
      options={items}
      onRemove={onClick}
      components={{
        Option,
        Menu,
        MenuList,
        Control,
        Input,
        IndicatorsContainer,
        DropdownIndicator,
        IndicatorSeparator,
      }}
    />
  );
};

function Menu({ children, ...props }) {
  const dispatch = useDispatch();
  const { list, user } = props.selectProps;

  return (
    <components.Menu
      className={cn(Styles.menu, props.selectProps.classes?.menu)}
      {...props}
    >
      {children}
      <div
        onClick={function () {
          dispatch(
            roleDelete({
              data: {
                product_list_id: list.product_list_id,
                user: user.user_id,
              },
            })
          );
        }}
        className={cn(Styles.remove, StylesOption.option, "border-top", "ps-3")}
      >
        Remove
      </div>
    </components.Menu>
  );
}

const Option = (props) => {
  const { isSelected } = props;

  return (
    <components.Option
      className={cn(StylesOption.option, props.selectProps.classes?.option)}
      {...props}
    >
      <div className="d-flex gap-2">
        <SelectCheck
          className={cn("flex-shrink-0", { "opacity-0": !isSelected })}
        />
        {props.children}
      </div>
    </components.Option>
  );
};

const Control = function (props: any) {
  const RSControl = components.Control;

  return (
    <RSControl
      {...props}
      className={cn(
        "flex-nowrap",
        StylesControl.control,
        props.selectProps.classes?.control,
        {
          [Styles.control_opened]: props.menuIsOpen,
          [StylesControl.control_valid]: props.selectProps.isValid,
          [StylesControl.control_invalid]: props.selectProps.isInvalid,
        }
      )}
    />
  );
};
