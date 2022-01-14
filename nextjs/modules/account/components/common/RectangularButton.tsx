import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/common/RectangularButton.module.scss";

interface IRadioButtons {
  value: any;
  name: string;
  checkedValue: any;
  disabled?: boolean;
  onChange?: (e) => void;
}

interface IProps {
  onClick?: any;
  header?: React.ReactNode;
  body?: React.ReactNode;
  footer?: React.ReactNode;
  radioButton?: IRadioButtons;
  classNames?: {
    container?: any;
  };
}

const RectangularButton: React.FC<IProps> = ({
  onClick,
  header,
  body,
  footer,
  classNames,
  radioButton,
}) => {
  const classes = {
    container: [Styles.container, classNames?.container],
  };

  if (radioButton) {
    return (
      <label
        onClick={onClick}
        className={cn(classes.container, {
          [Styles.container_active]:
            radioButton.checkedValue === radioButton.value,
        })}
      >
        <input
          type="radio"
          value={radioButton.value}
          checked={radioButton.checkedValue === radioButton.value}
          onChange={radioButton.onChange}
          name={radioButton.name}
          disabled={radioButton.disabled}
          className={"d-none"}
        />
        {header}
        {body}
        {footer}
      </label>
    );
  }

  return (
    <div onClick={onClick} className={cn(classes.container)}>
      {header}
      {body}
      {footer}
    </div>
  );
};

export default RectangularButton;
