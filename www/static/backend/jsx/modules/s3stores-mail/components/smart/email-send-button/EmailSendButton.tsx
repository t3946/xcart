import React, { useContext, useState } from "react";
import { Button } from "@material-ui/core";
import ScheduleIcon from "@material-ui/icons/Schedule";
import KeyboardArrowUpIcon from "@material-ui/icons/KeyboardArrowUp";
import KeyboardArrowDownIcon from "@material-ui/icons/KeyboardArrowDown";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";
import { EmailDialogError } from "@s3stores-mail/components/smart/email-dialog-error/EmailDialogError";
import { useCLickListener } from "@s3stores-mail/hooks/useCLickListener";
import { EmailSendBodyContext } from "@s3stores-mail/contexts/email-send-body-context/EmailSendBody.context";
import { checkValidEmailRecipients } from "@s3stores-mail/utils/check-valid-email-recipients";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { emailStore } from "@redux/stores";

export const SendButton: React.FC<any> = () => {
  const [openScheduleButton, setOpenScheduleButton] = useState(false);

  const [open, setOpen] = React.useState(false);
  const { sendMessage, recipientsInputRef, addNewRecipient } =
    useContext(EmailSendBodyContext);

  const sendData = useSelector((state: StoreDto) => state.sendData);

  const sendError = checkValidEmailRecipients(sendData.to);

  const dialog = useContext(EmailDialogContext);

  const isValid = () => {
    return checkValidEmailRecipients(emailStore.getState().sendData.to).valid;
  };
  const addDataFromRecipientInput = () => {
    if (recipientsInputRef.current.value.trim()) {
      addNewRecipient(recipientsInputRef.current.value.trim());
      recipientsInputRef.current.value = "";
    }
  };

  const handleClickOpen = () => {
    if (sendError.valid) {
      dialog.handleClickOpen();
      return;
    }
    setOpen(true);
  };

  const handleClickSend = () => {
    addDataFromRecipientInput();
    if (isValid()) {
      sendMessage(sendData, "Message send");
      return;
    }
    setOpen(true);
  };

  const ErrorDialogHandleClose = () => {
    setOpen(false);
  };

  const handleClick = (event: React.MouseEvent<HTMLDivElement, MouseEvent>) => {
    event.stopPropagation();
    setOpenScheduleButton(!openScheduleButton);
  };

  useCLickListener(setOpenScheduleButton);

  return (
    <div className="send-button-wrapper">
      {openScheduleButton && (
        <div>
          <Button onClick={handleClickOpen} className="schedule-footer-button">
            <div className="schedule-wrap">
              <ScheduleIcon className="schedule-icon" />
              <span className="schedule-text">Schedule send</span>
            </div>
          </Button>
        </div>
      )}
      <Button className="send-button" onClick={handleClickSend}>
        <span className="send-button-text">Send</span>
        <div className="schedule-key-icon" onClick={handleClick}>
          {openScheduleButton ? (
            <KeyboardArrowUpIcon />
          ) : (
            <KeyboardArrowDownIcon />
          )}
        </div>
      </Button>

      <EmailDialogError
        errorText={sendError.error}
        open={open}
        handleClose={ErrorDialogHandleClose}
      />
    </div>
  );
};
