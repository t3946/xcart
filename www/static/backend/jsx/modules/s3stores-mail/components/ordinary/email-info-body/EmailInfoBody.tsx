import React, { useContext } from "react";
import { Paper } from "@material-ui/core";
import { EmailInfoBodyData } from "@s3stores-mail/components/simple/email-info-body-data/EmailInfoBodyData";
import { EmailInfoDataFooter } from "@s3stores-mail/components/smart/email-info-data-footer/EmailInfoDataFooter";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";
import { IncomingFilesList } from "../incoming-files-list/IncomingFilesList";
import { EmailThreadContext } from "@s3stores-mail/contexts/email-thread-context/EmailThread.context";

export const EmailInfoBody: React.FC<any> = () => {
  const {
    handleReply,
    handleClick,
    handleForward,
    templates,
    handleReplyByTemplate,
  } = useContext(EmailInfoContext);
  const { emailInfo } = useContext(EmailThreadContext);
  return (
    <React.Fragment>
      <Paper elevation={0} square={true}>
        <div className="email-info-data-wrapper">
          <EmailInfoBodyData />
        </div>
        {emailInfo.attachment !== [] && (
          <div className="email-info-data-wrapper">
            <IncomingFilesList files={emailInfo.attachment} />
          </div>
        )}
        <div className="email-info-data-wrapper">
          <EmailInfoDataFooter
            handleReply={handleReply}
            handleClick={handleClick}
            handleForward={handleForward}
            templates={templates[0]}
          />
        </div>
      </Paper>
    </React.Fragment>
  );
};
