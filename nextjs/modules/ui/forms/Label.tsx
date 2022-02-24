import React from "react";
import cn from "classnames";
import Styles from "@modules/ui/forms/Label.module.scss";

interface IProps {
  children: string | React.ReactNode;
  required?: boolean;
  optional?: boolean;
  error?: boolean;
  className?: any;
}
const Label: React.FC<IProps> = ({
  children,
  required,
  optional,
  error,
  className,
}) => {
  return (
    <label
      className={cn(Styles.formInputLabel, className, {
        [Styles.formInputLabel_optional]: optional,
        [Styles.formInputLabel_required]: required,
        [Styles.formInputLabel_error]: error,
      })}
    >
      {children}
    </label>
  );
};

export default Label;
