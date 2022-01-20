import React, { useEffect, useState } from "react";
import useCLickListener from "@modules/account/hooks/useClickListener";
import classnames from "classnames";
import { SelectValue } from "@modules/account/ts/types/select-value.type";
import { FormikErrors } from "formik";
import Input from "@modules/ui/forms/Input";
import Styles from "@modules/ui/forms/Select.module.scss";

interface Item {
  viewValue: string;
  previewValue?: string;
  value: any;
}

interface IProps {
  items: Item[] | SelectValue<any, any>[];
  onClick?: (item: Item) => void | any;
  value: any;
  name?: any;
  errorMessage?: string | FormikErrors<any> | string[] | FormikErrors<any>[];
  label?: any;
  disabled?: boolean;
  isValid?: boolean;
  isInvalid?: boolean;
  classes?: {
    input?: any;
    selectHeader?: any;
    selectList?: any;
  };
  id?: any;
}

const Select: React.FC<IProps> = ({
  items,
  onClick,
  value,
  errorMessage,
  name = null,
  classes = undefined,
  id = undefined,
  disabled,
  isValid,
  isInvalid,
}: IProps) => {
  const selectedItem = value;
  const [open, setOpen] = useState(false);

  const clickListener = useCLickListener(setOpen, id);

  useEffect(() => {
    clickListener && clickListener.startListen();

    return () => {
      clickListener && clickListener.endListen();
    };
  });

  return (
    <div style={{ width: "100%" }} className={classnames(classes?.input)}>
      <div
        onClick={(e) => {
          e.stopPropagation();
          if (!disabled) {
            setOpen(!open);
          }
        }}
        className={classnames("select-wrapper", Styles.chevron, {
          [Styles.chevron_rotate]: open,
        })}
      >
        <Input
          id={id}
          name={name}
          value={selectedItem?.previewValue || selectedItem?.viewValue}
          disabled={disabled}
          isValid={isValid}
          isInvalid={isInvalid}
          readOnly
          className={classnames(
            classes?.selectHeader,
            `${errorMessage && "form-input_error"}`,
            { "cursor-default": disabled, "cursor-pointer": !disabled }
          )}
        />

        {open && (
          <ul
            className={classnames(
              "form-select-list",
              "d-block",
              classes?.selectList
            )}
          >
            {items.map((item, i) => (
              <li
                onClick={() => onClick(item)}
                className="form-select-item"
                key={`${i}_${item.viewValue}`}
              >
                {item.viewValue}
              </li>
            ))}
          </ul>
        )}
      </div>
    </div>
  );
};

export default Select;
