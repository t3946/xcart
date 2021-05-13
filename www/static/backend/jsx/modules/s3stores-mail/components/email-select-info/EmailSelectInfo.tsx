import React, { useState } from "react";

export const EmailSelectInfo = () => {
  const [open, setOpen] = useState(false);

  const [value, setValue] = useState("123");
  return (
    <div className="select select-info" onClick={() => setOpen(!open)}>
      <input value={value} className="select__input" type="hidden" name="" />
      <div className="select__head">{value}</div>
      <ul className={`select__list ${open && "open"}`}>
        <li className="select__group">
          <li className="select__group-title">123123123</li>

          <li value={1} className="select__item">
            1
          </li>
          <li value={3} className="select__item">
            2
          </li>
          <li value={2} className="select__item">
            3
          </li>
        </li>
      </ul>
    </div>
  );
};
