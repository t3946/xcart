import React, { Fragment, useContext } from "react";
import { Form, Row } from "react-bootstrap";
import { FraudCheckOrderContext } from "@admin/modules/order-fraud/contexts/FraudCheckOrderContext";
interface FraudRadioField {
  fraudCode: string;
  section: string;
}
export const FraudRadioField: React.FC<FraudRadioField> = ({
  fraudCode,
  section,
}) => {
  const { fraudManual, setFraudManual } = useContext(FraudCheckOrderContext);
  return (
    <Fragment>
      <input
        type="radio"
        checked={
          fraudManual[section] && fraudManual[section][fraudCode] === "Y"
        }
        value="Y"
        data-section={section}
        data-field={fraudCode}
        onChange={setFraudManual}
      />
      Yes
      <br />
      <input
        type="radio"
        data-section={section}
        data-field={fraudCode}
        checked={
          fraudManual[section] && fraudManual[section][fraudCode] === "N"
        }
        onChange={setFraudManual}
        value="N"
      />
      No
    </Fragment>
  );
};
