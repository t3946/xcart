import React from "react";
import { Button, Grid, Paper } from "@material-ui/core";
import moment from "moment";
import { EmailAttachmentItem } from "@modules/account/components/orders/EmailAttachmentItem";
import { Iframe } from "@modules/account/components/shared/Ifame";

interface EmailBodyProps {
  emailInfo: any;
  contentRef: any;
}

export const EmailBody: React.FC<EmailBodyProps> = ({
  emailInfo,
  contentRef,
}) => {
  return (
    <Paper ref={contentRef} elevation={0} square={true}>
      <div className="email-info-data-wrapper">
        <div>
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
                  {moment(emailInfo.date).format("ddd, MMM, h:mm")}
                  &nbsp;
                  {`(${moment(emailInfo.date).fromNow()})`}
                </span>
              </Grid>
            </Grid>
          </Grid>
          <Iframe src={emailInfo.body} />
        </div>
      </div>
      {emailInfo.attachment !== [] && (
        <div className="email-info-data-wrapper">
          <div className="attachment-list-wrapper">
            {emailInfo.attachment.map((e) => {
              if (e.cid === null) {
                return (
                  <div>
                    <EmailAttachmentItem file={e} />
                  </div>
                );
              }
            })}
          </div>
        </div>
      )}
    </Paper>
  );
};
