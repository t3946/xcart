import React, { Fragment, useContext } from "react";
import { Form, Row } from "react-bootstrap";
import { FraudCheckOrderContext } from "@admin/modules/order-fraud/contexts/FraudCheckOrderContext";
interface FraudRadioField {
  fraudCode: string;
}
export const FraudRadioField: React.FC<FraudRadioField> = ({ fraudCode }) => {
  const { fraudManual, setFraudManual } = useContext(FraudCheckOrderContext);
  return (
    <Fragment>
      <input
        type="radio"
        checked={fraudManual[fraudCode] === "Y"}
        value="Y"
        data-field={fraudCode}
        onChange={setFraudManual}
      />
      Yes
      <br />
      <input
        type="radio"
        data-field={fraudCode}
        checked={fraudManual[fraudCode] === "N"}
        onChange={setFraudManual}
        value="N"
      />
      No
    </Fragment>
  );
};
