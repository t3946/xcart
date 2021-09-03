import React, { Fragment, useContext, useRef, useState } from "react";
import { EmailInfoHeader } from "@s3stores-mail/components/ordinary";
import { EmailInfoBody } from "@s3stores-mail/components/ordinary/email-info-body/EmailInfoBody";
import { Collapse } from "@material-ui/core";
import { EmailDto } from "@s3stores-mail/ts/types/email.type";
import { EmailThreadContext } from "@s3stores-mail/contexts/email-thread-context/EmailThread.context";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";
export interface EmailInfoWrapper {
  emailInfo: EmailDto;
  openEmail?: boolean;
}
export const EmailInfoWrapper: React.FC<EmailInfoWrapper> = ({
  emailInfo,
  openEmail,
}) => {
  const [open, setOpen] = useState(openEmail ?? false);
  const templateRef = useRef();
  const { handleView } = useContext(EmailInfoContext);
  const onHideOrCloseEmail = (emailInfo: EmailDto) => {
    if (!open && !emailInfo.viewed) {
      handleView(emailInfo);
    }
    setOpen(!open);
  };
  return (
    <Fragment>
      <EmailThreadContext.Provider
        value={{
          emailInfo,
          templateRef,
          open: { get: open, set: onHideOrCloseEmail },
        }}
      >
        <EmailInfoHeader />
        <Collapse in={open}>
          <EmailInfoBody emailInfo={emailInfo} />
        </Collapse>
      </EmailThreadContext.Provider>
    </Fragment>
  );
};
