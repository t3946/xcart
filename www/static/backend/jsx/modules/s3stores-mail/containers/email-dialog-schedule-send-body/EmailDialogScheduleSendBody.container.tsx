import React from "react";
import { SendRadioGroup } from "@s3stores-mail/components/ordinary/email-dialog-schelude-send-radio-group/RadioGroup";
import { TimePicker } from "@s3stores-mail/components/smart/email-dialog-schedule-time-picker/TimePicker";
import { ScheduleSendButtons } from "@s3stores-mail/components/ordinary/email-dialog-schedule-send-buttons/ScheduleSendButtons";
import { useDispatch, useSelector } from "react-redux";
import { editSendData } from "@redux/actions";
import { StoreDto } from "@s3stores-mail/ts/types";
import { setScheduleTime, switchValue } from "@s3stores-mail/utils";

export const EmailDialogScheduleSendBodyContainer: React.FC = () => {
  const dispatch = useDispatch();

  const sendData = useSelector((state: StoreDto) => state.sendData);

  const handleDateChange = (value: Date) => {
    dispatch(editSendData(value, "date"));
  };

  const handleDateChangeRadioGroup = (value: any) => {
    dispatch(
      editSendData(
        new Date(setScheduleTime(switchValue(value)).format()),
        "date"
      )
    );
  };

  return (
    <div className="schedule-body-wrap">
      <SendRadioGroup
        date={sendData.date}
        handleDateChange={handleDateChangeRadioGroup}
      />
      <div className="schedule-time-wrap">
        <TimePicker date={sendData.date} handleDateChange={handleDateChange} />
      </div>
      <ScheduleSendButtons sendData={sendData} />
    </div>
  );
};
