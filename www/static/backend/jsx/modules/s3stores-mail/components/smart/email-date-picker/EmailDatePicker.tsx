import React from "react";
import { DatePicker, MuiPickersUtilsProvider } from "@material-ui/pickers";
import DateFnsUtils from "@date-io/date-fns";
import "date-fns";

export const EmailDatePicker: React.FC<any> = ({
  value,
  handleDateChange,
  label,
  name,
  min = undefined,
  max = undefined,
}) => {
  return (
    <MuiPickersUtilsProvider utils={DateFnsUtils}>
      <DatePicker
        label={label}
        fullWidth
        value={value}
        maxDate={max}
        name={name}
        minDate={min}
        onChange={handleDateChange}
        autoOk
        animateYearScrolling
        inputVariant="outlined"
      />
    </MuiPickersUtilsProvider>
  );
};
