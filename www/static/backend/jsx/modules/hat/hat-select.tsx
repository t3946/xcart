import React, { useState } from "react";
import { SelectItemDto } from "@s3stores-mail/ts/types";
import { useCLickListener } from "@s3stores-mail/hooks/useCLickListener";

interface EmailSelectSendDto {
  items: SelectItemDto[];
  name?: string;
}

export const HatSelect: React.FC<any> = ({ items, name }) => {
  const [open, setOpen] = useState(false);

  const [value, setValue] = useState(items[0]);
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
        value={JSON.stringify(value)}
        className="select__input"
        type="hidden"
        name={name}
      />
      <div className="select__head hat">{value.viewValue}</div>
      <ul className={`select__list hat`}>
        {items.map((item) => {
          return (
            <li onClick={() => setValue(item)} className="select__item">
              {item.viewValue}
            </li>
          );
        })}
      </ul>
    </div>
  );
};
