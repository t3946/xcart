import React from "react";
import { SendRadioGroup } from "../email-dialog-schelude-send-radio-group/RadioGroup";
import { TimePicker } from "../email-dialog-schedule-time-picker/TimePicker";
import { ScheduleSendButtons } from "../email-dialog-schedule-send-buttons/ScheduleSendButtons";

export const EmailDialogScheduleSendBody: React.FC = () => {
  return (
    <div className="schedule-body-wrap">
      <SendRadioGroup />
      <div className="schedule-time-wrap">
        <TimePicker />
      </div>
      <ScheduleSendButtons />
    </div>
  );
};
