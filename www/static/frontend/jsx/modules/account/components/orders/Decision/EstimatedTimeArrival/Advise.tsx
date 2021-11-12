import React from "react";
import Replace from "@client/modules/icon/components/account/replace/Replace";
import Truck from "@client/modules/icon/components/account/truck/Truck";
import Ban from "@client/modules/icon/components/account/ban/Ban";
import Clock from "@client/modules/icon/components/account/clock/Clock";
import classnames from "classnames";
import { Form as RBForm } from "react-bootstrap";

export enum AdviseTypes {
  wait = "wait",
  ship = "ship",
  cancel = "cancel",
  replace = "replace",
}

interface PropsInterface {
  type: AdviseTypes | string;
  className: any;
  value: string;
  name: string;
  checked?: boolean;
  onChange: any;
}

const Advise: React.FC<PropsInterface> = (props: PropsInterface) => {
  const { type, className, value, name, checked, onChange } = props;

  function iconTemplate() {
    const iconClasses = [
      "estimate-advise-button__icon",
      "estimate-advise-icon",
      "estimate-advise-icon_" + type,
    ];
    switch (type) {
      case AdviseTypes.wait:
        return <Clock className={classnames(iconClasses)} />;
      case AdviseTypes.ship:
        return <Truck className={classnames(iconClasses)} />;
      case AdviseTypes.cancel:
        return <Ban className={classnames(iconClasses)} />;
      case AdviseTypes.replace:
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
      { "estimate-advise-radio-marker_checked": checked },
    ],
    button: [
      "estimate-advise-button",
      "estimate-advise__button",
      {
        "estimated-advise-button_wait": type === AdviseTypes.wait && checked,
        "estimated-advise-button_ship": type === AdviseTypes.ship && checked,
        "estimated-advise-button_cancel":
          type === AdviseTypes.cancel && checked,
        "estimated-advise-button_replace":
          type === AdviseTypes.replace && checked,
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
      />

      <div className={classnames(classes.button)}>
        {iconTemplate()}
        {textTemplate()}
      </div>
    </label>
  );
};

export default Advise;
