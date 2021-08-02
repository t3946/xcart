import React, { useState } from "react";
import { useCLickListener } from "@s3stores-mail/hooks/useCLickListener";
import classNames from "classnames";

export const HatSelect: React.FC<any> = (props) => {
  const { items, name, className } = props;
  const [open, setOpen] = useState(false);
  const [value, setValue] = useState(items[0]);
  const searchSelectTaxonomyClasses = classNames([
    className,
    "select select-send search-select-taxonomy",
    { open },
  ]);

  useCLickListener(setOpen);

  return (
    <div
      className={searchSelectTaxonomyClasses}
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

      <div className="select__head hat search-select-taxonomy-button">
        {value.viewValue}
      </div>

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
