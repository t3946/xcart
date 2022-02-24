import React from "react";
import Advice, {
  AdviceTypes,
} from "@modules/account/components/orders/Decision/EstimatedTimeArrival/Advice";
import classnames from "classnames";

interface IProps {
  value: string;
  onChange: any;
  name: string;
  hasInStock: boolean;
  hasOutOfStock: boolean;
  hasDiscontinued: boolean;
  className?: any;
  disabled?: boolean;
}

const AdviceList: React.FC<IProps> = function (props: IProps) {
  const {
    onChange,
    name,
    value,
    hasInStock,
    hasOutOfStock,
    hasDiscontinued,
    className,
  } = props;
  const disabled = props.disabled || false;
  const printAdviseMap = {};

  printAdviseMap[AdviceTypes.replace] = hasOutOfStock;
  printAdviseMap[AdviceTypes.ship] = hasInStock;
  printAdviseMap[AdviceTypes.wait] = hasOutOfStock;
  printAdviseMap[AdviceTypes.cancel] =
    (hasInStock || hasOutOfStock) && (hasOutOfStock || hasDiscontinued);

  const options = [];

  for (const printAdviseMapKey in printAdviseMap) {
    if (printAdviseMap[printAdviseMapKey]) {
      options.push(
        <Advice
          type={printAdviseMapKey}
          className={"advise-list__item"}
          value={printAdviseMapKey}
          name={name}
          checked={value === printAdviseMapKey}
          onChange={onChange}
          disabled={disabled}
          key={printAdviseMapKey}
        />
      );
    }
  }

  return <div className={classnames(className)}>{options}</div>;
};

export default AdviceList;
