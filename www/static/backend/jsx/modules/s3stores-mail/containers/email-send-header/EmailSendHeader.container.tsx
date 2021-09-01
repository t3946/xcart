import React from "react";
import { EmailSendHeaderDialogContext } from "@s3stores-mail/contexts/email-send-header-context/EmailSendHeaderDialog.context";
import { EmailSendHeader } from "@s3stores-mail/components/smart/email-send-header/EmailSendHeader";
import { useDispatch } from "react-redux";
import { setSendTemplate, setSendTemplateType } from "@redux/actions";

export const EmailSendHeaderContainer: React.FC = () => {
  const dispatch = useDispatch();

  const setTemplateType = (item) => {
    dispatch(setSendTemplateType(item));
    dispatch(
      setSendTemplate({
        template_name: undefined,
        message_body: "",
      })
    );
  };

  const setTemplate = (item) => {
    dispatch(setSendTemplate(item));
  };

  const headerContext = {
    setTemplate,
    setTemplateType,
  };
  return (
    <EmailSendHeaderDialogContext.Provider value={headerContext}>
      <EmailSendHeader />
    </EmailSendHeaderDialogContext.Provider>
  );
};
