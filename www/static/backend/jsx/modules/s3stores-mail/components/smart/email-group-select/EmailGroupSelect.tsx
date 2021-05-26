import React, { useEffect, useState } from "react";
import { EmailGroupSelectOption } from "@s3stores-mail/components/ordinary/email-group-select-option/EmailGroupSelectOption";

export const EmailGroupSelect: React.FC<any> = ({
  items,
  value,
  type,
  onClick,
}) => {
  const [selectOpen, setSelectOpen] = useState(false);

  useEffect(() => {
    window.addEventListener("click", () => {
      setSelectOpen(false);
    });
    return window.removeEventListener("click", () => {
      setSelectOpen(false);
    });
  }, []);

  return (
    <React.Fragment>
      {items && (
        <div
          className={`select select-${type}  ${selectOpen && "open"}`}
          onClick={(e) => {
            e.stopPropagation();
            setSelectOpen(!selectOpen);
          }}
        >
          <input
            value={value}
            className="select__input"
            type="hidden"
            name=""
          />
          <div className="select__head">{value.viewValue}</div>
          <ul className={`select__list ${selectOpen && "open"}`}>
            {items.map((group) => {
              return (
                <EmailGroupSelectOption group={group} onOptionClick={onClick} />
              );
            })}
          </ul>
        </div>
      )}
    </React.Fragment>
  );
};
