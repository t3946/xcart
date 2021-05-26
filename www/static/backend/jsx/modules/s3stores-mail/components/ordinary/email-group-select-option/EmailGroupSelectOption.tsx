import React from "react";

export const EmailGroupSelectOption: React.FC<any> = ({
  group,
  onOptionClick,
}) => {
  return (
    <li className="select__group">
      <li className="select__group-title">{group.viewValue}</li>
      {group.items.map((item) => {
        return (
          <li onClick={() => onOptionClick(item)} className="select__item">
            {item.viewValue}
          </li>
        );
      })}
    </li>
  );
};
