import React, { useContext, useEffect, useState } from "react";
import { Button } from "@material-ui/core";
import ScheduleIcon from "@material-ui/icons/Schedule";
import KeyboardArrowUpIcon from "@material-ui/icons/KeyboardArrowUp";
import KeyboardArrowDownIcon from "@material-ui/icons/KeyboardArrowDown";
import { ScheduleDialogContext } from "@s3stores-mail/contexts";
import { EmailScheduleSendDialogHOC } from "@s3stores-mail/hoc/EmailScheduleSendDialogHOC";

const SendButton: React.FC = () => {
  useEffect(() => {
    window.addEventListener("click", () => {
      setOpenScheduleButton(false);
    });
    return window.removeEventListener("click", () => {
      setOpenScheduleButton(false);
    });
  }, []);

  const [openScheduleButton, setOpenScheduleButton] = useState(false);

  const dialog = useContext(ScheduleDialogContext);

  const handleClick = (event: React.MouseEvent<HTMLDivElement, MouseEvent>) => {
    event.stopPropagation();
    setOpenScheduleButton(!openScheduleButton);
  };

  return (
    <div>
      <Button className="send-button">
        <span className="send-button-text">Send</span>
        <div className="schedule-key-icon" onClick={handleClick}>
          {openScheduleButton ? (
            <KeyboardArrowUpIcon />
          ) : (
            <KeyboardArrowDownIcon />
          )}
        </div>
      </Button>
      {openScheduleButton && (
        <div>
          <Button
            onClick={dialog.handleClickOpen}
            className="schedule-footer-button"
          >
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

export const EmailSendButton = EmailScheduleSendDialogHOC(<SendButton />);
