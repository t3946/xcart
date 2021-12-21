import React from "react";
import moment from "moment";
import { EmailAttachmentItem } from "@modules/account/components/orders/EmailAttachmentItem";
import { Iframe } from "@modules/account/components/shared/Ifame";

import Styles from "@modules/account/components/orders/EmailBody.module.scss"

interface EmailBodyProps {
  emailInfo: any;
  contentRef: any;
}

export const EmailBody: React.FC<EmailBodyProps> = ({
  emailInfo,
  contentRef,
}) => {
  return (
    <div className={Styles.emailInfoDataContainer} ref={contentRef}>
      <div className="email-info-data-wrapper">
        <div>
          <div className="d-flex justify-content-center">
            <div className="email-title-wrap">
              <div className="d-flex">
                <span className="email-info-from">from:</span>
                <span className="email-info-title-text">
                  {emailInfo.from_address}
                </span>
              </div>
              <div className="d-flex">
                <span className="email-info-to">To:</span>
                <span className="email-info-title-text">
                  {emailInfo.to_address}
                </span>
              </div>
            </div>
            <div>
              <div className="d-flex">
                <span className="email-info-title-text">
                  {moment(emailInfo.date).format("ddd, MMM, h:mm")}
                  &nbsp;
                  {`(${moment(emailInfo.date).fromNow()})`}
                </span>
              </div>
            </div>
          </div>
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
    </div>
  );
};
