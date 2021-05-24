import React, { useState } from "react";
import { FormControlLabel, Radio, RadioGroup } from "@material-ui/core";
import { RadioText } from "../email-dialog-schedule-send-radio-text/RadioText";
import { useDispatch, useSelector } from "react-redux";
import moment from "moment";
import { editSendData } from "@redux/actions";
import { setScheduleTime, switchValue } from "@s3stores-mail/utils";
import { StoreDto } from "@s3stores-mail/ts/types";

export const SendRadioGroup: React.FC = () => {
  const dispatch = useDispatch();

  const handleDateChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setRadioValue((event.target as HTMLInputElement).value);
    dispatch(
      editSendData(
        new Date(
          setScheduleTime(
            switchValue((event.target as HTMLInputElement).value)
          ).format()
        ),
        "date"
      )
    );
  };

  const selectedDate = useSelector((state: StoreDto) => {
    return state.sendData.date;
  });

  const [radioValue, setRadioValue] = useState("1");

  return (
    <RadioGroup
      value={radioValue}
      onChange={handleDateChange}
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
        label={
          <RadioText
            label={"Pick Time"}
            time={moment(selectedDate).calendar()}
          />
        }
      />
    </RadioGroup>
  );
};
