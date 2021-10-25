import React, { useEffect, useState } from "react";
import useCLickListener from "../../hooks/useClickListener";
import { Grid } from "@material-ui/core";
import classnames from "classnames";

interface Item {
  viewValue: string;
  previewValue: string;
  value: any;
}

interface PropsInterface {
  items: Item[];
  onClick?: (item: Item) => any;
  value: any;
  name: any;
  label?: any;
  classes?: {
    input?: any;
    group?: any;
    selectHeader?: any;
    selectList?: any;
  };
  id?: any;
}

export const FormSelect: React.FC<PropsInterface> = ({
  items,
  onClick,
  value,
  name = null,
  label = null,
  classes = undefined,
  id = undefined,
}: PropsInterface) => {
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
    <Grid
      className={classnames(
        `select select-send ${open && "open"} justify-content-between`,
        classes?.group
      )}
      container
      alignItems="center"
    >
      {label && <label className="form-input-label">{label}</label>}
      <div
        onClick={(e) => {
          e.stopPropagation();
          setOpen(!open);
        }}
        className={classnames("select-wrapper", classes?.input)}
      >
        <input
          value={selectedItem}
          className="select__input"
          type="hidden"
          name={name}
        />
        <div
          id={id}
          className={classnames(classes?.selectHeader, "form-select-head")}
        >
          {selectedItem?.previewValue || selectedItem?.viewValue}
        </div>
        {open && (
          <ul className={classnames("form-select-list", classes?.selectList)}>
            {items.map((item) => {
              return (
                <li onClick={() => onClick(item)} className="form-select-item">
                  {item.viewValue}
                </li>
              );
            })}
          </ul>
        )}
      </div>
    </Grid>
  );
};
