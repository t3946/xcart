import React, { useCallback } from "react";
import { EmailSendBodyContext } from "@s3stores-mail/contexts/email-send-body-context/EmailSendBody.context";
import { EmailSendBody } from "@s3stores-mail/components/simple/email-send-body/EmailSendBody";
import { addFile } from "@redux/actions";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";

export const EmailSendBodyContainer: React.FC = () => {
  const dispatch = useDispatch();
  const onDrop = useCallback(([acceptedFile]) => {
    dispatch(addFile(acceptedFile));
  }, []);

  const sendData = useSelector((state: StoreDto) => state.sendData);

  const context = {
    onDrop,
    files: sendData.files,
    sendData: sendData,
  };

  return (
    <EmailSendBodyContext.Provider value={context}>
      <EmailSendBody />
    </EmailSendBodyContext.Provider>
  );
};
