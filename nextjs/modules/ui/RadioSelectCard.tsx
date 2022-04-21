import React from "react";
import RadioButton from "@modules/ui/RadioButton";
import CardHeader from "@modules/account/components/wallet/CardHeader";

interface IProps {
  name: string;
  cards: any;
  checkedValue: any;
  onChange: (e: any) => void;
  defaultCardId?: string;
  disabled?: boolean;
  classes?: any;
}

const RadioSelectCard: React.FC<IProps> = (props: IProps) => {
  const {
    name,
    cards,
    checkedValue,
    defaultCardId,
    disabled = false,
    onChange,
  } = props;

  function cardTemplate(card: any) {
    return (
      <div className={"d-flex mb-2"} key={`card-${card.id}`}>
        <label className={"d-flex align-items-center cursor-pointer"}>
          <RadioButton
            name={name}
            value={card.id}
            checkedValue={checkedValue}
            onChange={onChange}
            disabled={disabled}
            classes={"me-2"}
          />
          <CardHeader cardLast4={card.last4} cardType={card.brand} />
        </label>
      </div>
    );
  }

  const templates = [];

  for (const card of cards) {
    if (card.id === defaultCardId) {
      templates.unshift(cardTemplate(card));
    } else {
      templates.push(cardTemplate(card));
    }
  }

  return templates;
};

export default RadioSelectCard;
