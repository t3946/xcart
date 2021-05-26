import React from "react";
import { DateTimePicker, MuiPickersUtilsProvider } from "@material-ui/pickers";
import { Grid } from "@material-ui/core";
import DateFnsUtils from "@date-io/date-fns";
import "date-fns";

export const TimePicker: React.FC<any> = ({ handleDateChange, date }) => {
  return (
    <Grid alignItems="center" container justify="space-between">
      <MuiPickersUtilsProvider utils={DateFnsUtils}>
        <DateTimePicker
          fullWidth
          autoOk
          ampm={false}
          disablePast
          value={date}
          onChange={handleDateChange}
          label="24h clock"
        />
      </MuiPickersUtilsProvider>
    </Grid>
  );
};
