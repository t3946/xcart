import React, { useEffect, useState } from "react";
import useCLickListener from "../../hooks/useClickListener";
import classnames from "classnames";
import { SelectValue } from "@client/modules/account/ts/types/select-value.type";
import { FormikErrors } from "formik";

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
}: IProps) => {
  const selectedItem = value;
  const [open, setOpen] = useState(false);

  const clickListener = useCLickListener(setOpen, id);

  useEffect(() => {
    clickListener.startListen();

    return () => {
      clickListener.endListen();
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
            setOpen(!open);
          }}
          className={classnames("select-wrapper")}
        >
          <input
            value={selectedItem}
            className="select__input"
            type="hidden"
            name={name}
          />
          <div
            id={id}
            className={classnames(
              classes?.selectHeader,
              "form-select-head",
              `${errorMessage && "form-input_error"}`
            )}
          >
            {selectedItem?.previewValue || selectedItem?.viewValue}
          </div>
          {open && (
            <ul className={classnames("form-select-list", classes?.selectList)}>
              {items.map((item) => {
                return (
                  <li
                    onClick={() => onClick(item)}
                    className="form-select-item"
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
