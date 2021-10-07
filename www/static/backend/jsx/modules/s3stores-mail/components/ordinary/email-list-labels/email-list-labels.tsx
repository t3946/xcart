import React, { Fragment } from "react";
import { EmailLabel } from "@s3stores-mail/ts/types/email.type";
interface EmailListLabels {
  labels: EmailLabel[];
}
export const EmailListLabels: React.FC<EmailListLabels> = ({ labels }) => {
  return (
    <Fragment>
      {labels.map((label) => (
        <div
          className="label-item-email-wrapper"
          style={{ backgroundColor: label.background_color }}
        >
          <span
            className="label-item-email-text"
            style={{ color: label.color }}
          >
            {label.name}
          </span>
        </div>
      ))}
    </Fragment>
  );
};
