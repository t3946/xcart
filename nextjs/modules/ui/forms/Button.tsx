import React from "react";
import cn from "classnames";
import Styles from "@modules/ui/forms/Button.module.scss";

interface IProps {
  className?: any;
  type?: any;
  theme?: ETheme;
  disabled?: boolean;
  onClick?: any;
  children?: any;
}

export enum ETheme {
  outlined = "outline",
  micro = "micro",
  themeDarkGrey = "themeDarkGrey",
  themeGrey = "themeGrey",
  wide = "wide",
}

const Button: React.FC<IProps> = function (props: IProps) {
  const { className, theme, type = "button", disabled, onClick } = props;
  const classes = [className, Styles.button, Styles[`button_${theme}`]];

  return (
    <button
      className={cn(classes)}
      disabled={disabled}
      onClick={onClick}
      type={type}
    >
      {props.children}
    </button>
  );
};

export default Button;
