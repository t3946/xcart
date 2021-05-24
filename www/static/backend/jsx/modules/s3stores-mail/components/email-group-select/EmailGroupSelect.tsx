import React, { useEffect, useState } from "react";

export const EmailGroupSelect: React.FC<any> = ({
  items,
  type,
  value,
  onClick,
}) => {
  useEffect(() => {
    window.addEventListener("click", () => {
      setSelectOpen(false);
    });
    return window.removeEventListener("click", () => {
      setSelectOpen(false);
    });
  }, []);

  const [selectOpen, setSelectOpen] = useState(false);

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
                <li className="select__group">
                  <li className="select__group-title">{group.viewValue}</li>
                  {group.items.map((item) => {
                    return (
                      <li
                        onClick={() => onClick(item)}
                        className="select__item"
                      >
                        {item.viewValue}
                      </li>
                    );
                  })}
                </li>
              );
            })}
          </ul>
        </div>
      )}
    </React.Fragment>
  );
};
