import React, { useEffect, useState } from "react";
import { SelectItemDto } from "@s3stores-mail/ts/types";

interface EmailSelectSendDto {
  items: SelectItemDto[];
  value: SelectItemDto;
  onChange: (item: SelectItemDto) => void;
}

export const EmailSelectSend: React.FC<EmailSelectSendDto> = ({
  items,
  value,
  onChange,
}) => {
  useEffect(() => {
    window.addEventListener("click", () => {
      setOpen(false);
    });
    return window.removeEventListener("click", () => {
      setOpen(false);
    });
  }, []);

  const [open, setOpen] = useState(false);

  return (
    <div
      className={`select select-send ${open && "open"}`}
      onClick={(e) => {
        e.stopPropagation();
        setOpen(!open);
      }}
    >
      <input
        value={value.value}
        className="select__input"
        type="hidden"
        name=""
      />
      <div className="select__head">{value.viewValue}</div>
      <ul className={`select__list`}>
        {items.map((item) => {
          return (
            <li onClick={() => onChange(item)} className="select__item">
              {item.viewValue}
            </li>
          );
        })}
      </ul>
    </div>
  );
};
