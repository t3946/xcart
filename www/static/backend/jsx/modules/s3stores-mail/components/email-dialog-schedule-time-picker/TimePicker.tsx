import React from "react";
import { DateTimePicker, MuiPickersUtilsProvider } from "@material-ui/pickers";
import { useDispatch, useSelector } from "react-redux";
import { editSendData } from "@redux/actions";
import { Grid } from "@material-ui/core";
import DateFnsUtils from "@date-io/date-fns";
import "date-fns";
import { StoreDto } from "@s3stores-mail/ts/types";

export const TimePicker: React.FC = () => {
  const dispatch = useDispatch();

  const handleDateChange = (value: Date) => {
    dispatch(editSendData(value, "date"));
  };

  const selectedDate = useSelector((state: StoreDto) => state.sendData.date);

  return (
    <Grid alignItems="center" container justify="space-between">
      <MuiPickersUtilsProvider utils={DateFnsUtils}>
        <DateTimePicker
          fullWidth
          autoOk
          ampm={false}
          disablePast
          value={selectedDate}
          onChange={handleDateChange}
          label="24h clock"
        />
      </MuiPickersUtilsProvider>
    </Grid>
  );
};
