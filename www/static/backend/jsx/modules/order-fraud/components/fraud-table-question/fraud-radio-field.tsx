import React, { Fragment } from "react";
import { useDispatch, useSelector } from "react-redux";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";
import { changeAnswerResult } from "@redux/actions/fraudCheckActions";
interface FraudRadioField {
  fraudCode: string;
  // section: string;
}
export const FraudRadioField: React.FC<FraudRadioField> = ({
  fraudCode,
  // section,
}) => {
  const dispatch = useDispatch();
  const fraudResultChange = useSelector(
    (state: FraudCheckStore) => state.data.resultChange
  );
  return (
    <Fragment>
      <input
        type="radio"
        checked={fraudResultChange[fraudCode] === "Y"}
        value="Y"
        // data-section={section}
        data-field={fraudCode}
        onChange={(e) => dispatch(changeAnswerResult(e))}
      />
      Yes
      <br />
      <input
        type="radio"
        // data-section={section}
        data-field={fraudCode}
        checked={fraudResultChange[fraudCode] === "N"}
        onChange={(e) => dispatch(changeAnswerResult(e))}
        value="N"
      />
      No
    </Fragment>
  );
};
