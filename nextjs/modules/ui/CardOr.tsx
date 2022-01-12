import React from "react";
import Card from "@modules/ui/Card";
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
    or: [
      "d-flex",
      "align-items-center",
      "justify-content-center",
      "mx-auto",
      Styles.or,
    ],
  };

  return (
    <div className={cn(classes.block)}>
      <Card
        classes={{ cardBody: props.classes.card }}
        radioButton={
          props.radioButtons && {
            checkedValue: props.radioButtons.checkedValue,
            value: props.radioButtons.valueSecond,
            name: props.radioButtons.name,
            onChange: props.radioButtons.onChange,
            disabled: false,
          }
        }
      >
        {props.cardFirst}
      </Card>

      <div className={cn(classes.or)}>or</div>
      <Card
        classes={{ cardBody: props.classes.card }}
        radioButton={
          props.radioButtons && {
            checkedValue: props.radioButtons.checkedValue,
            value: props.radioButtons.valueFirst,
            name: props.radioButtons.name,
            onChange: props.radioButtons.onChange,
            disabled: false,
          }
        }
      >
        {props.cardSecond}
      </Card>
    </div>
  );
};

export default CardOr;
