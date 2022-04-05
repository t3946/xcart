import React from "react";
import Styles from "@modules/ui/RadioButton.module.scss";
import cn from "classnames";

interface IProps {
  name: string;
  value: any;
  checkedValue: any;
  onChange: (e: any) => void;
  disabled?: boolean;
  classes?: any;
}

const RadioButton: React.FC<IProps> = (props: IProps) => {
  const { name, value, checkedValue, disabled = false, onChange } = props;

  const classes = [
    Styles.radioMarker,
    props.classes,
    {
      [Styles.radioMarker_checked]: checkedValue === value,
      [Styles.radioMarker_disabled]: disabled,
    },
  ];

  return (
    <>
      <div className={cn(classes)} />

      <input
        type="radio"
        value={value}
        disabled={disabled}
        onChange={onChange}
        name={name}
        className={"d-none"}
        checked={checkedValue === value}
      />
    </>
  );
};

export default RadioButton;
