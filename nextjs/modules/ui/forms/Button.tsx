import React from "react";
import cn from "classnames";
import Styles from "@modules/ui/forms/Button.module.scss";

interface IProps {
  className?: any;
  type?: EType;
  disabled?: boolean;
  onClick?: any;
  children?: any;
}

export enum EType {
  outlined = "outlined",
  micro = "micro",
  themeDarkGrey = "themeDarkGrey",
  themeGrey = "themeGrey",
  wide = "wide",
}

const Button: React.FC<IProps> = function (props: IProps) {
  const { className, type, disabled, onClick } = props;
  const classes = [className, Styles.button, Styles[`button_${type}`]];

  return (
    <button className={cn(classes)} disabled={disabled} onClick={onClick}>
      {props.children}
    </button>
  );
};

export default Button;
