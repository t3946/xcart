import React from "react";
import RadioButton from "@modules/ui/RadioButton";
import Styles from "@modules/ui/CardOr.module.scss";
import cn from "classnames";

interface IRadioButtons {
  valueFirst: string;
  valueSecond: string;
  name: string;
  checkedValue: string;
  disabled: boolean;
  onChange: (e) => void;
  className?: any;
}

interface IProps {
  classes: any;
  cardFirst: React.ReactNode;
  cardSecond: React.ReactNode;
  radioButtons?: IRadioButtons;
}

const CardOr: React.FC<IProps> = (props) => {
  const classes = {
    block: ["align-items-center", Styles.cardLayout, props.classes.block],
    card: [Styles.card],
    or: [
      "d-flex",
      "align-items-center",
      "justify-content-center",
      "text-uppercase",
      "mx-auto",
      Styles.or,
    ],
  };
  if (props.radioButtons) {
    classes.card = [...classes.card, Styles.card_radio];

    const RadioCardBody = (value: string, children: React.ReactNode) => {
      return (
        <label
          className={cn([
            "d-flex",
            "align-items-center",
            props.radioButtons.className,
            Styles.cursor_pointer,
            Styles.cardBody,
            props.classes.card,
          ])}
        >
          <RadioButton
            value={value}
            name={props.radioButtons.name}
            checkedValue={props.radioButtons.checkedValue}
            onChange={props.radioButtons.onChange}
            disabled={props.radioButtons.disabled}
            classes={Styles.radioMarker}
          />
          {children}
        </label>
      );
    };

    return (
      <div className={cn(classes.block)}>
        <div
          className={cn([
            classes.card,
            {
              [Styles.card_radio_active]:
                props.radioButtons.valueFirst ===
                props.radioButtons.checkedValue,
            },
          ])}
        >
          {RadioCardBody(props.radioButtons.valueFirst, props.cardFirst)}
        </div>
        <div className={cn(classes.or)}>or</div>
        <div
          className={cn(classes.card, {
            [Styles.card_radio_active]:
              props.radioButtons.valueSecond ===
              props.radioButtons.checkedValue,
          })}
        >
          {RadioCardBody(props.radioButtons.valueSecond, props.cardSecond)}
        </div>
      </div>
    );
  }
  return (
    <div className={cn(classes.block)}>
      <div className={cn(classes.card)}>
        <div className={cn([Styles.cardBody, props.classes.card])}>
          {props.cardFirst}
        </div>
      </div>
      <div className={cn(classes.or)}>or</div>
      <div className={cn([Styles.cardBody, props.classes.card])}>
        {props.cardSecond}
      </div>
    </div>
  );
};

export default CardOr;
