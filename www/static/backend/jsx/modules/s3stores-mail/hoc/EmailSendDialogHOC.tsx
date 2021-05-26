import React from "react";
import { EmailSend } from "../containers/email-send/EmailSend";
import { useDispatch } from "react-redux";
import { EmailSendDialogContext } from "@s3stores-mail/contexts";
import { resetSendData } from "@redux/actions";

export const EmailSendDialogHOC: (component: React.ReactNode) => React.FC = (
  component: React.ReactNode
) => {
  return () => {
    const dispatch = useDispatch();
    const [open, setOpen] = React.useState(false);

    const handleClickOpen = () => {
      setOpen(true);
    };

    const handleClose = () => {
      setOpen(false);
      dispatch(resetSendData());
    };

    const sendDialog = {
      open,
      handleClickOpen,
      handleClose,
    };

    return (
      <EmailSendDialogContext.Provider value={sendDialog}>
        {component}
        <EmailSend />
      </EmailSendDialogContext.Provider>
    );
  };
};
