import React from "react";
import { EmailDto } from "@s3stores-mail/ts/types/email.type";
interface EmailThreadContext {
  emailInfo: EmailDto;
  templateRef: any;
  open: { get: boolean; set: (emailInfo: EmailDto) => void };
}
export const EmailThreadContext: React.Context<EmailThreadContext> =
  React.createContext({
    emailInfo: null,
    templateRef: null,
    open: null,
  });
