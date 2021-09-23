import React from "react";
import { EmailSendBodyForm } from "@s3stores-mail/components/ordinary/email-send-body-form/EmailSendBodyForm";
import { EmailSendFooter } from "@s3stores-mail/components/ordinary/email-send-footer/EmailSendFooter";

export const EmailSendBody: React.FC = () => {
  return (
    <div className="email-send-body-wrapper">
      <div className="email-send-body-form">
        <EmailSendBodyForm />
      </div>
      <EmailSendFooter />
    </div>
  );
};
