import React, { useState } from "react";
import { useCLickListener } from "@s3stores-mail/hooks/useCLickListener";

export const EmailSelect: React.FC<any> = ({ items, onClick, value, name }) => {
  const [open, setOpen] = useState(false);

  useCLickListener(setOpen);

  return (
    <div
      className={`select select-send ${open && "open"}`}
      onClick={(e) => {
        e.stopPropagation();
        setOpen(!open);
      }}
    >
      <input
        value={value}
        className="select__input"
        type="hidden"
        name={name}
      />
      <div className="select__head">{value.name}</div>
      <ul className={`select__list`}>
        {items.map(([item]) => {
          return (
            <li onClick={() => onClick(item)} className="select__item">
              {item.name}
            </li>
          );
        })}
      </ul>
    </div>
  );
};
