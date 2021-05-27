import React, { useContext } from "react";
import { Paper } from "@material-ui/core";
import { EmailInfoBodyData } from "@s3stores-mail/components/simple/email-info-body-data/EmailInfoBodyData";
import { EmailInfoDataFooter } from "@s3stores-mail/components/smart/email-info-data-footer/EmailInfoDataFooter";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";

export const EmailInfoBody: React.FC<any> = ({ emailInfo }) => {
  const { handleReply, handleClick, handleForward } = useContext(
    EmailInfoContext
  );

  return (
    <React.Fragment>
      <Paper elevation={0} square={true} className="email-info-data-wrapper">
        <EmailInfoBodyData data={emailInfo.item} />
        <EmailInfoDataFooter
          handleReply={handleReply}
          handleClick={handleClick}
          handleForward={handleForward}
        />
      </Paper>
    </React.Fragment>
  );
};
