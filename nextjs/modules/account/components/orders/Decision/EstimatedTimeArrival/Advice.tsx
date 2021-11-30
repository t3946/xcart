import React from "react";
import Replace from "@modules/icon/components/account/replace/Replace";
import Truck from "@modules/icon/components/account/truck/Truck";
import Ban from "@modules/icon/components/account/ban/Ban";
import Clock from "@modules/icon/components/account/clock/Clock";
import classnames from "classnames";
import { Form as RBForm } from "react-bootstrap";

export enum AdviceTypes {
  wait = "wait",
  ship = "ship",
  cancel = "cancel",
  replace = "replace",
}

interface IProps {
  type: AdviceTypes | string;
  className: any;
  value: string;
  name: string;
  checked?: boolean;
  onChange: any;
  disabled?: boolean;
}

const Advice: React.FC<IProps> = (props: IProps) => {
  const { type, className, value, name, checked, onChange } = props;
  const disabled = props.disabled || false;

  function iconTemplate() {
    const iconClasses = [
      "estimate-advise-button__icon",
      "estimate-advise-icon",
      "estimate-advise-icon_" + type,
    ];
    switch (type) {
      case AdviceTypes.wait:
        return <Clock className={classnames(iconClasses)} />;
      case AdviceTypes.ship:
        return <Truck className={classnames(iconClasses)} />;
      case AdviceTypes.cancel:
        return <Ban className={classnames(iconClasses)} />;
      case AdviceTypes.replace:
        return <Replace className={classnames(iconClasses)} />;
    }
  }

  function textTemplate() {
    const text = {
      wait: "Wait for 'out of stock' items and then process the order",
      ship: "Ship 'in stock' items and remove 'out of stock' items",
      cancel: "Cancel and void transaction for the whole order",
      replace:
        "Replace 'out of stock' item(s) with alternative one(s) and process the order",
    };

    return (
      <div className={"estimate-advise-button__text estimate-advise-text"}>
        {text[type]}
      </div>
    );
  }

  const classes = {
    label: [
      "estimated-advise",
      "d-flex",
      "align-items-center",
      "w-100",
      className,
    ],
    marker: [
      "estimate-advise-radio-marker",
      {
        "estimate-advise-radio-marker_checked": checked,
        "estimate-advise-radio-marker_disabled": disabled,
      },
    ],
    button: [
      "estimate-advise-button",
      "estimate-advise__button",
      {
        "estimated-advise-button_wait": type === AdviceTypes.wait && checked,
        "estimated-advise-button_ship": type === AdviceTypes.ship && checked,
        "estimated-advise-button_cancel":
          type === AdviceTypes.cancel && checked,
        "estimated-advise-button_replace":
          type === AdviceTypes.replace && checked,
      },
    ],
  };

  return (
    <label className={classnames(classes.label)}>
      <div className={classnames(classes.marker)} />

      <RBForm.Check
        type="radio"
        value={value}
        name={name}
        onChange={onChange}
        className={"d-none"}
        checked={checked}
        disabled={disabled}
      />

      <div className={classnames(classes.button)}>
        {iconTemplate()}
        {textTemplate()}
      </div>
    </label>
  );
};

export default Advice;
