import React, { useState } from "react";
import { useCLickListener } from "../../hooks/useClikcListener";
import { Grid } from "@material-ui/core";

export const FormSelect = ({
  items,
  onClick,
  value,
  name,
  label,
  width = "100%",
}) => {
  const [open, setOpen] = useState(false);

  useCLickListener(setOpen);
  return (
    <Grid
      className={`select select-send ${open && "open"}`}
      onClick={(e) => {
        e.stopPropagation();
        setOpen(!open);
      }}
      container
      alignItems="center"
      justify="space-between"
      value={value}
    >
      {label && <label className="form-input-label">{label}</label>}
      <div
        style={{
          width,
        }}
      >
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
