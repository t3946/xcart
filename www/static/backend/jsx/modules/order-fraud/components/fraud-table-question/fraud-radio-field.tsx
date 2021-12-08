import React, { Fragment } from "react";
import { useDispatch, useSelector } from "react-redux";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";
import { changeAnswerResult } from "@redux/actions/fraudCheckActions";
interface FraudRadioField {
  fraudCode: string;
}
export const FraudRadioField: React.FC<FraudRadioField> = ({ fraudCode }) => {
  const dispatch = useDispatch();
  const fraudResultChange = useSelector(
    (state: FraudCheckStore) => state.data.resultChange
  );
  return (
    <Fragment>
      <input
        type="radio"
        data-field={fraudCode}
        checked={fraudResultChange[fraudCode] === "N"}
        onChange={(e) => dispatch(changeAnswerResult(e))}
        value="N"
      />
      No
      <br />
      <input
        type="radio"
        checked={fraudResultChange[fraudCode] === "Y"}
        value="Y"
        data-field={fraudCode}
        onChange={(e) => dispatch(changeAnswerResult(e))}
      />
      Yes
    </Fragment>
  );
};
