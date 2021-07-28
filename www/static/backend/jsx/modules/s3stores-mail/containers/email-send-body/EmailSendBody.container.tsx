import React, { useCallback, useContext, useRef } from "react";
import { EmailSendBodyContext } from "@s3stores-mail/contexts/email-send-body-context/EmailSendBody.context";
import { EmailSendBody } from "@s3stores-mail/components/simple/email-send-body/EmailSendBody";
import {
  addFile,
  addRecipient,
  deleteRecipient,
  editRecipient,
  editSendData,
  sendEmail,
} from "@redux/actions";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";
import { SnackbarContext } from "@s3stores-mail/contexts/snackbar/Snackbar.context";

export const EmailSendBodyContainer: React.FC = () => {
  const dispatch = useDispatch();
  const onDrop = useCallback(([acceptedFile]) => {
    dispatch(addFile(acceptedFile));
  }, []);

  const filesRef = useRef<HTMLDivElement>();

  const dialog = useContext(EmailDialogContext);

  const { showSnackbar } = useContext(SnackbarContext);

  const changeField = (field, value) => {
    dispatch(editSendData(value, field));
  };

  const addNewRecipient = (value) => {
    dispatch(addRecipient(value));
  };

  const editThisRecipient = (value, newValue) => {
    dispatch(editRecipient(value, newValue));
  };

  const deleteThisRecipient = (value) => {
    dispatch(deleteRecipient(value));
  };

  const sendMessage = (value, message: string) => {
    dispatch(sendEmail(value));
    dialog.handleClose();
    showSnackbar(message, "success");
  };

  const sendTemplate = useSelector((state: StoreDto) => state.sendTemplate);

  const context = {
    onDrop,
    sendTemplate,
    changeField,
    addNewRecipient,
    editThisRecipient,
    deleteThisRecipient,
    sendMessage,
    filesRef,
  };

  return (
    <EmailSendBodyContext.Provider value={context}>
      <EmailSendBody />
    </EmailSendBodyContext.Provider>
  );
};
