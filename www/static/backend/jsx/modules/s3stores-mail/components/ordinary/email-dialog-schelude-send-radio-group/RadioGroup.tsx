import React, { useState } from "react";
import { FormControlLabel, Radio, RadioGroup } from "@material-ui/core";
import moment from "moment";
import { setScheduleTime } from "@s3stores-mail/utils";
import { RadioText } from "@s3stores-mail/components/simple/email-dialog-schedule-send-radio-text/RadioText";

export const SendRadioGroup: React.FC<any> = ({ handleDateChange, date }) => {
  const [radioValue, setRadioValue] = useState("1");

  const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setRadioValue((event.target as HTMLInputElement).value);
    handleDateChange((event.target as HTMLInputElement).value);
  };

  return (
    <RadioGroup
      value={radioValue}
      onChange={handleChange}
      aria-label="gender"
      name="gender1"
    >
      <FormControlLabel
        className="radio-item"
        value="1"
        control={<Radio color="default" />}
        label={
          <RadioText
            label={"Morning of the next working day"}
            time={setScheduleTime(8).calendar()}
          />
        }
      />
      <FormControlLabel
        className="radio-item"
        value="2"
        control={<Radio color="default" />}
        label={
          <RadioText
            label={"Morning of the next working day"}
            time={setScheduleTime(9).calendar()}
          />
        }
      />
      <FormControlLabel
        className="radio-item"
        value="3"
        control={<Radio color="default" />}
        label={<RadioText label={"Pick Time"} time={moment(date).calendar()} />}
      />
    </RadioGroup>
  );
};
