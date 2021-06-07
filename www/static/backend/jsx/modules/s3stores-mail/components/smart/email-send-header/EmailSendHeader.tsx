import React, { useContext } from "react";
import { Grid } from "@material-ui/core";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { EmailGroupSelect } from "@s3stores-mail/components/smart/email-group-select/EmailGroupSelect";
import { EmailDialogHeader } from "@s3stores-mail/components/simple/email-dialog-header/EmailDialogHeader";
import { EmailSelect } from "@s3stores-mail/components/smart/email-select/EmailSelect";
import { EmailSendHeaderDialogContext } from "@s3stores-mail/contexts/email-send-header-context/EmailSendHeaderDialog.context";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";

export const EmailSendHeader: React.FC = () => {
  const dialog = useContext(EmailDialogContext);
  const { setTemplateType, setTemplate } = useContext(
    EmailSendHeaderDialogContext
  );

  const templateType = useSelector((state: StoreDto) => state.templateType);

  const sendTemplate = useSelector((state: StoreDto) => state.sendTemplate);

  const templates = useSelector((state: StoreDto) => {
    return state.templates;
  });

  return (
    <EmailDialogHeader handleClose={dialog.handleClose}>
      <Grid
        className="email-send-header-children"
        alignItems="center"
        container
        xs={10}
      >
        <Grid className="email-send-header-text">Select template:</Grid>
        <Grid className={"email-send-template-type"}>
          <EmailSelect
            items={templates}
            value={templateType}
            onClick={(item) => setTemplateType(item)}
          />
        </Grid>
        <Grid>
          <EmailGroupSelect
            onClick={(item) => setTemplate(item)}
            value={sendTemplate}
            type="send"
            items={templateType?.items}
          />
        </Grid>
      </Grid>
    </EmailDialogHeader>
  );
};
