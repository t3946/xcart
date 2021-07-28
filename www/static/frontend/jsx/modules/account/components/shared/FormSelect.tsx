import React, { useState } from "react";
import useCLickListener from "../../hooks/useClickListener";
import { Grid } from "@material-ui/core";
import classnames from "classnames";

export const FormSelect = ({
  items,
  onClick,
  value,
  name,
  label = "",
  classes = undefined,
}) => {
  const [open, setOpen] = useState(false);

  useCLickListener(setOpen);
  return (
    <Grid
      onClick={(e) => {
        e.stopPropagation();
        setOpen(!open);
      }}
      className={classnames(
        `select select-send ${open && "open"}`,
        classes?.group
      )}
      container
      alignItems="center"
      justify="space-between"
    >
      {label && <label className="form-input-label">{label}</label>}
      <div className={classnames("select-wrapper", classes?.input)}>
        <input
          value={value}
          className="select__input"
          type="hidden"
          name={name}
        />
        <div className="select__head">{value.viewValue}</div>
        <ul className={`select__list`}>
          {items.map((item) => {
            return (
              <li onClick={() => onClick(item)} className="select__item">
                {item.viewValue}
              </li>
            );
          })}
        </ul>
      </div>
    </Grid>
  );
};
