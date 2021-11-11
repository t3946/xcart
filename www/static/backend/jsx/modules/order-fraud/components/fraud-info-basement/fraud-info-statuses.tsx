import React from "react";
import { Grid } from "@material-ui/core";
import { useDispatch, useSelector } from "react-redux";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";
import {
  changeFraudCheckStatus,
  updateFraudCheckStatus,
} from "@redux/actions/fraudCheckActions";

export const FraudInfoStatuses: React.FC = ({}) => {
  const dispatch = useDispatch();
  const orderId = useSelector((state: FraudCheckStore) => state.orderId);
  const statuses = useSelector(
    (state: FraudCheckStore) => state.data.settings.statusList
  );
  const { fraudStatus } = useSelector(
    (state: FraudCheckStore) => state.data.orderInfo
  );
  return (
    <div className="fraud-info-status-block">
      <Grid
        container
        direction="row"
        justifyContent="space-between"
        alignItems="flex-start"
      >
        <div>
          <Grid
            container
            justifyContent="center"
            direction="row"
            alignItems="center"
          >
            <span>Change fraud check status to:</span>
            <div className="select-statuses">
              <select
                onChange={(e) =>
                  dispatch(changeFraudCheckStatus(e.target.value))
                }
                value={fraudStatus.code}
              >
                {statuses.map((status) => (
                  <option value={status.code}>{status.name}</option>
                ))}
              </select>
            </div>
          </Grid>
        </div>
        <div>
          <button
            onClick={() =>
              dispatch(updateFraudCheckStatus(orderId, fraudStatus.code))
            }
          >
            Apply changes, update fraud scores and change fraud check status
          </button>
        </div>
      </Grid>
    </div>
  );
};
