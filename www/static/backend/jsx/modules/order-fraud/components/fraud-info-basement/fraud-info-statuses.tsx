import React, { useContext } from "react";
import { Button, FormControl, Grid, MenuItem, Select } from "@material-ui/core";
import { BootstrapInput } from "@admin/modules/order-fraud/ts/consts/info-basement";
import { FraudCheckOrderContext } from "@admin/modules/order-fraud/contexts/FraudCheckOrderContext";
interface FraudInfoStatuses {
  select: { get: string; set: (event) => void };
  saveStatus: () => void;
}
export const FraudInfoStatuses: React.FC<FraudInfoStatuses> = ({
  select,
  saveStatus,
}) => {
  const { settings } = useContext(FraudCheckOrderContext);
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
              <select onChange={select.set} value={select.get}>
                {settings.status &&
                  settings.status.map((status) => (
                    <option value={status.code}>{status.name}</option>
                  ))}
              </select>
            </div>
          </Grid>
        </div>
        <div>
          <button onClick={saveStatus}>
            Apply changes, update fraud scores and change fraud check status
          </button>
        </div>
      </Grid>
    </div>
  );
};
