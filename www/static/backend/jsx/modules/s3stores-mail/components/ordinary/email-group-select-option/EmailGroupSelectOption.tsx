import React from "react";

export const EmailGroupSelectOption: React.FC<any> = ({
  group,
  onOptionClick,
}) => {
  return (
    <li className="select__group">
      <li className="select__group-title">{group.name}</li>
      {group.templates.map((item) => {
        return (
          <li onClick={() => onOptionClick(item)} className="select__item">
            {item.template_name}
          </li>
        );
      })}
    </li>
  );
};
