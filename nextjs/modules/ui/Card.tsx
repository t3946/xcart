import React from "react";
import RadioButton from "@modules/ui/RadioButton";
import Styles from "@modules/ui/Card.module.scss";
import cn from "classnames";

interface IProps {
  radioButton?: {
    value: any;
    name: string;
    checkedValue: any;
    onChange: (e) => void;
    disabled: boolean;
  };
  classes?: {
    card?: any;
    cardBody?: any;
    radioButton?: any;
  };
}

const Card: React.FC<IProps> = (props) => {
  if (props.radioButton) {
    return (
      <div className={cn(Styles.card, props.classes?.card)}>
        <label
          className={cn([
            "d-flex",
            "align-items-center",
            Styles.cardBody,
            props.classes?.cardBody,
            Styles.card_radio,
            {
              [Styles.card_radio_active]:
                props.radioButton.value === props.radioButton.checkedValue,
              [Styles.cursor_pointer]:
                props.radioButton.value !== props.radioButton.checkedValue,
            },
          ])}
        >
          <RadioButton
            value={props.radioButton.value}
            name={props.radioButton.name}
            checkedValue={props.radioButton.checkedValue}
            onChange={props.radioButton.onChange}
            disabled={props.radioButton.disabled}
            classes={Styles.radioMarker}
          />
          {props.children}
        </label>
      </div>
    );
  }
  return (
    <div className={cn(Styles.card, props.classes?.card)}>
      <div className={cn([Styles.cardBody, props.classes?.cardBody])}>
        {props.children}
      </div>
    </div>
  );
};

export default Card;
