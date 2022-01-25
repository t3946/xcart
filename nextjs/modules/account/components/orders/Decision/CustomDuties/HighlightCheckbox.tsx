import React from "react";
import Checkbox from "@modules/ui/forms/Checkbox";
import cn from "classnames";

import Styles from "@modules/account/components/orders/Decision/CustomDuties/HighlightCheckbox.module.scss";

interface IProps {
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  checked: boolean;
  disabled: boolean;
  className?: any;
  label: string | React.ReactNode;
}

const HighlightCheckbox: React.FC<IProps> = ({
  onChange,
  checked,
  disabled,
  className,
  label,
}) => {
  return (
    <label
      className={cn(
        Styles.checkboxContainer,
        "d-block",
        "cursor-pointer",
        className,
        {
          [Styles.checkboxContainer_active]: checked,
        }
      )}
    >
      <Checkbox
        name="agreement"
        checked={checked}
        onChange={onChange}
        disabled={disabled}
        label={label}
      />
    </label>
  );
};

export default HighlightCheckbox;
