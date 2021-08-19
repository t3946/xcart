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
            <Select
              labelId="demo-customized-select-label"
              id="demo-customized-select"
              value={select.get}
              onChange={select.set}
              input={<BootstrapInput />}
            >
              {settings.status &&
                settings.status.map((status) => (
                  <MenuItem value={status.code}>{status.name}</MenuItem>
                ))}
            </Select>
          </div>
        </Grid>
      </div>
      <div>
        <Button onClick={saveStatus} size="small" variant="contained">
          Apply changes, update fraud scores and change fraud check status
        </Button>
      </div>
    </Grid>
  );
};
