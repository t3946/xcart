import React, { useContext } from "react";
import { Grid } from "@material-ui/core";
import AlternateEmailIcon from "@material-ui/icons/AlternateEmail";
import { EmailSendFileUpload } from "@s3stores-mail/components/smart/email-send-file-upload/EmailSendFileUpload";
import { EmailSendBodyContext } from "@s3stores-mail/contexts/email-send-body-context/EmailSendBody.context";
import { SendButton } from "@s3stores-mail/components/smart";
import { EmailDialogHOC } from "@s3stores-mail/hoc";
import { EmailDialogScheduleSend } from "@s3stores-mail/containers";

export const EmailSendFooter: React.FC = () => {
  const { onDrop } = useContext(EmailSendBodyContext);

  const { changeField } = useContext(EmailSendBodyContext);

  const EmailSendButton = EmailDialogHOC(
    <SendButton />,
    <EmailDialogScheduleSend />,
    () => {
      changeField("date", null);
    }
  );

  return (
    <Grid className="email-send-footer-wrapper" alignItems="center" container>
      <EmailSendButton />
      <EmailSendFileUpload onDrop={onDrop} />
    </Grid>
  );
};
