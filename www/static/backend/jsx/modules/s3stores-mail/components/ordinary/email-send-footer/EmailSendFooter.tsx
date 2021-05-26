import React, { useContext } from "react";
import { Grid } from "@material-ui/core";
import AlternateEmailIcon from "@material-ui/icons/AlternateEmail";
import { EmailSendFileUpload } from "@s3stores-mail/components/smart/email-send-file-upload/EmailSendFileUpload";
import { EmailSendFilesList } from "@s3stores-mail/components/ordinary/email-send-files-list/EmailSendFilesList";
import { EmailSendButton } from "@s3stores-mail/components/smart/email-send-button/EmailSendButton";
import { EmailSendBodyContext } from "@s3stores-mail/contexts/email-send-body-context/EmailSendBody.context";

export const EmailSendFooter: React.FC = () => {
  const { files, onDrop } = useContext(EmailSendBodyContext);
  return (
    <React.Fragment>
      <Grid alignItems="center" container className="b">
        <Grid className="email-send-body-footer-text">
          <span>Attachment</span>
        </Grid>
        <Grid alignItems="center" container xs={2}>
          <AlternateEmailIcon className="a" />
          <EmailSendFileUpload onDrop={onDrop} />
        </Grid>
      </Grid>
      <EmailSendFilesList files={files} />
      <EmailSendButton />
    </React.Fragment>
  );
};
