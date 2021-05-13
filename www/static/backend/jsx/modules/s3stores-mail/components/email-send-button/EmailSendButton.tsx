import React, { useState } from "react";
import { Button, Paper } from "@material-ui/core";
import ScheduleIcon from "@material-ui/icons/Schedule";
import KeyboardArrowUpIcon from "@material-ui/icons/KeyboardArrowUp";
import KeyboardArrowDownIcon from "@material-ui/icons/KeyboardArrowDown";

export const EmailSendButton = () => {
  const [open, setOpen] = useState(false);
  return (
    <div>
      <Button className="send-button" onClick={() => console.log(1)}>
        <span className="send-button-text">Send</span>
        <div className="schedule-key-icon" onClick={() => setOpen(!open)}>
          {open ? <KeyboardArrowUpIcon /> : <KeyboardArrowDownIcon />}
        </div>
      </Button>
      {open && (
        <div>
          <Button className="schedule-footer-button">
            <div className="schedule-wrap">
              <ScheduleIcon className="schedule-icon" />
              <span className="schedule-text">Schedule send</span>
            </div>
          </Button>
        </div>
      )}
    </div>
  );
};
