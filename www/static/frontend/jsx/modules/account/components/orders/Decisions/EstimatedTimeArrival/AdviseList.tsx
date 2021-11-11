import React from "react";
import Advise, {
  AdviseTypes,
} from "@client/modules/account/components/orders/Decisions/EstimatedTimeArrival/Advise";
import classnames from "classnames";

interface PropsInterface {
  value: string;
  onChange: any;
  name: string;
  hasInStock: boolean;
  hasOutOfStock: boolean;
  hasDiscontinued: boolean;
  className?: any;
}

const AdviseList: React.FC<PropsInterface> = function (props: PropsInterface) {
  const {
    onChange,
    name,
    value,
    hasInStock,
    hasOutOfStock,
    hasDiscontinued,
    className,
  } = props;
  const printAdviseMap = {};

  printAdviseMap[AdviseTypes.replace] = hasOutOfStock;
  printAdviseMap[AdviseTypes.ship] = hasInStock;
  printAdviseMap[AdviseTypes.wait] = hasOutOfStock;
  printAdviseMap[AdviseTypes.cancel] =
    (hasInStock || hasOutOfStock) && (hasOutOfStock || hasDiscontinued);

  const options = [];

  for (const printAdviseMapKey in printAdviseMap) {
    if (printAdviseMap[printAdviseMapKey]) {
      options.push(
        <Advise
          type={printAdviseMapKey}
          className={"advise-list__item"}
          value={printAdviseMapKey}
          name={name}
          checked={value === printAdviseMapKey}
          onChange={onChange}
        />
      );
    }
  }

  return <div className={classnames(className)}>{options}</div>;
};

export default AdviseList;
