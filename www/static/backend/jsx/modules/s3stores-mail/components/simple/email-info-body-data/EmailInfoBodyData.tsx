import React, { useContext } from "react";
import { Grid } from "@material-ui/core";
import { Iframe } from "@s3stores-mail/components/smart/iframe/Iframe";
import moment from "moment";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";
import { EmailDto } from "../../../ts/types/email.type";
import { EmailThreadContext } from "@s3stores-mail/contexts/email-thread-context/EmailThread.context";

export const EmailInfoBodyData: React.FC<any> = () => {
  const { templateRef, emailInfo } = useContext(EmailThreadContext);
  return (
    <div ref={templateRef}>
      <Grid container justify="space-between">
        <Grid className="email-title-wrap">
          <Grid container>
            <span className="email-info-from">from:</span>
            <span className="email-info-title-text">
              {emailInfo.from_address}
            </span>
          </Grid>
          <Grid container>
            <span className="email-info-to">To:</span>
            <span className="email-info-title-text">
              {emailInfo.to_address}
            </span>
          </Grid>
        </Grid>
        <Grid>
          <Grid container>
            <span className="email-info-title-text">
              {moment(emailInfo.date).format("ddd, Do MMM, h:mm")}
              &nbsp;
              {`(${moment(emailInfo.date).fromNow()})`}
            </span>
          </Grid>
        </Grid>
      </Grid>
      <Iframe src={emailInfo.body} />
    </div>
  );
};
