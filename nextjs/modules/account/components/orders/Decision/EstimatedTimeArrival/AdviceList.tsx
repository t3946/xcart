import React from "react";
import Advice, {
  AdviceTypes,
} from "@modules/account/components/orders/Decision/EstimatedTimeArrival/Advice";
import classnames from "classnames";
import { ECases } from "@modules/account/components/orders/Decision/EstimatedTimeArrival/EstimatedTimeArrival";

interface IProps {
  value: string;
  onChange: any;
  name: string;
  caseCode: ECases;
  className?: any;
  disabled?: boolean;
}

const AdviceList: React.FC<IProps> = function (props: IProps) {
  const { onChange, name, value, caseCode, className } = props;
  const disabled = props.disabled || false;
  const options = [];
  const advices = {
    wait: {
      text: "Wait for 'out of stock' items and then process the order",
      action: "wait",
      type: "wait",
    },
    waitDiscontinued: {
      text: "Remove 'discontinued' items, wait for the 'out of stock' items, and then process order",
      action: "wait-discontinued",
      type: "wait",
    },
    shipOutOfStock: {
      text: "Ship 'in stock' items and remove 'out of stock' items",
      action: "ship",
      type: "ship",
    },
    shipDiscontinued: {
      text: "Ship 'in stock' items and issue a refund for discontinued / 'out of stock' items",
      action: "ship-discontinued",
      type: "ship",
    },
    shipDiscontinuedOutOfStock: {
      text: "Ship 'in stock' items and remove both discontinued and 'out of stock' items",
      action: "ship-discontinued-out-of-stock",
      type: "ship",
    },
    cancel: {
      text: "Cancel and void transaction for the whole order",
      action: "cancel",
      type: "cancel",
    },
  };

  function getAdviceTemplate(options: any) {
    const { text, action, type } = options;
    console.log({text, action, type});

    return (
      <Advice
        type={type}
        className={"advise-list__item"}
        value={action}
        name={name}
        checked={value === action}
        onChange={onChange}
        disabled={disabled}
        key={`advice-${action}`}
        text={text}
      />
    );
  }

  switch (caseCode) {
    case ECases.IN_STOCK_OUT_OF_STOCK:
      options.push(getAdviceTemplate(advices.wait));
      options.push(getAdviceTemplate(advices.shipOutOfStock));
      options.push(getAdviceTemplate(advices.cancel));
      break;
    case ECases.IN_STOCK_OUT_OF_STOCK_DISCONTINUED:
      options.push(getAdviceTemplate(advices.waitDiscontinued));
      options.push(getAdviceTemplate(advices.shipDiscontinuedOutOfStock));
      options.push(getAdviceTemplate(advices.cancel));
      break;
    case ECases.OUT_OF_STOCK_DISCONTINUED:
      options.push(getAdviceTemplate(advices.waitDiscontinued));
      options.push(getAdviceTemplate(advices.cancel));
      break;
    case ECases.IN_STOCK_DISCONTINUED:
      options.push(getAdviceTemplate(advices.shipDiscontinued));
      options.push(getAdviceTemplate(advices.cancel));
      break;
  }

  return <div className={classnames(className)}>{options}</div>;
};

export default AdviceList;
