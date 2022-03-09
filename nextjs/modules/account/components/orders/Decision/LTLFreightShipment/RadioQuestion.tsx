import React from "react";
import RadioButton from "@modules/ui/RadioButton";
import cn from "classnames";
import Label from "@modules/ui/forms/Label";
import Feedback from "@modules/ui/forms/Feedback";

import LTLFreightShipmentStyles from "@modules/account/components/orders/Decision/LTLFreightShipment/LTLFreightShipment.module.scss";
import Styles from "@modules/account/components/orders/Decision/LTLFreightShipment/RadioQuestion.module.scss";

interface IProps {
  question: {
    label?: string;
    name?: string;
    ext?: string;
    dependency?: {
      question: string;
      value: string;
    };
    radios: {
      label: string;
      value: string;
    }[];
  };
  error?: string | false;
  checkedValues: Record<string, string>;
  disabled: boolean;
  onChange: (e) => void;
  classes?: {
    container?: any;
    card?: any;
  };
}

const RadioQuestion: React.FC<IProps> = (props) => {
  const questionKey = props.question.name || props.question.label || "";

  if (props.question.dependency) {
    if (
      props.checkedValues[props.question.dependency.question].value !==
      props.question.dependency.value
    ) {
      return <></>;
    }
  }

  return (
    <div className={cn(Styles.question, props.classes?.container)}>
      {props.question.label && (
        <Label
          className={cn(
            Styles.questionLabel,
            Styles.question__label,
            "d-block",
            LTLFreightShipmentStyles.columnPadding,
            { [Styles.questionLabel_ext]: props.question.ext }
          )}
        >
          {props.question.label}{" "}
          {props.question.ext && (
            <>
              <div className={cn(Styles.questionLabelExt, "d-lg-none")}>
                {props.question.ext}
              </div>
              <span
                className={cn(
                  Styles.questionLabelExt,
                  "d-none",
                  "d-lg-inline-block"
                )}
              >
                ({props.question.ext})
              </span>
            </>
          )}
          {props.error && (
            <Feedback className={Styles.questionLabelFeedback} type="invalid">
              {props.error.value}
            </Feedback>
          )}
        </Label>
      )}
      <div className={cn(Styles.question__radios, "d-flex", "flex-dir-column")}>
        {props.question.radios.map((answer, i) => {
          return (
            <label
              key={i}
              className={cn(
                "d-flex",
                "align-items-center",
                "g-5",
                "cursor-pointer",
                Styles.radioLine,
                props.classes?.card
              )}
            >
              <RadioButton
                name={questionKey}
                value={answer.value}
                checkedValue={props.checkedValues[questionKey]?.value}
                disabled={props.disabled}
                onChange={() =>
                  props.onChange({
                    target: { name: questionKey, value: answer },
                  })
                }
              />
              <span>{answer.label}</span>
            </label>
          );
        })}
      </div>
    </div>
  );
};

export default RadioQuestion;
