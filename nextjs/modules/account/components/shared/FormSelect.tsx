import React, { useEffect, useState } from "react";
import useCLickListener from "../../hooks/useClickListener";
import classnames from "classnames";
import { SelectValue } from "@modules/account/ts/types/select-value.type";
import { FormikErrors } from "formik";
import Input from "@modules/ui/forms/Input";

import Styles from "@modules/account/components/shared/FormSelect.module.scss";

interface Item {
  viewValue: string;
  previewValue?: string;
  value: any;
}

interface IProps {
  items: Item[] | SelectValue<any, any>[];
  onClick?: (item: Item) => any;
  value: any;
  name?: any;
  errorMessage?: string | FormikErrors<any> | string[] | FormikErrors<any>[];
  label?: any;
  disabled?: boolean;
  isValid?: boolean;
  isInvalid?: boolean;
  classes?: {
    input?: any;
    group?: any;
    selectHeader?: any;
    selectList?: any;
  };
  id?: any;
}

export const FormSelect: React.FC<IProps> = ({
  items,
  onClick,
  value,
  errorMessage,
  name = null,
  label = null,
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
    <div
      className={classnames(
        `select select-send  alight-center d-flex ${
          open && "open"
        } justify-content-between`,
        classes?.group
      )}
    >
      {label && (
        <label
          className={`form-input-label ${
            errorMessage && "form-input-label-error"
          }`}
        >
          {label}
        </label>
      )}
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
            <ul className={classnames("form-select-list", classes?.selectList)}>
              {items.map((item, i) => {
                return (
                  <li
                    onClick={() => onClick(item)}
                    className="form-select-item"
                    key={`${i}_${item.viewValue}`}
                  >
                    {item.viewValue}
                  </li>
                );
              })}
            </ul>
          )}
        </div>
        {errorMessage && (
          <div className="error-message-input-container select-input-error-container">
            <div>
              <div className="form-input-caption">{errorMessage}</div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};
