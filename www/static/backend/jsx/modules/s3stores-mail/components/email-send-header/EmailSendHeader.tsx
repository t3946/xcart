import React, { useContext } from "react";
import { Grid } from "@material-ui/core";
import { EmailSelectSend } from "../email-select-send/EmailSelectSend";
import { EmailDialogHeader } from "../email-dialog-header/EmailDialogHeader";
import { selectSendFirstItems } from "@s3stores-mail/ts/consts";
import { useDispatch, useSelector } from "react-redux";
import { EmailSendDialogContext } from "@s3stores-mail/contexts";
import { setSendTemplate, setSendTemplateType } from "@redux/actions";
import { switchSendTemplateType } from "@s3stores-mail/utils";
import { EmailGroupSelect } from "@s3stores-mail/components/email-group-select/EmailGroupSelect";
import { StoreDto } from "@s3stores-mail/ts/types";

export const EmailSendHeader: React.FC = () => {
  const dialog = useContext(EmailSendDialogContext);
  const dispatch = useDispatch();

  const templateType = useSelector((state: StoreDto) => state.templateType);

  const sendTemplate = useSelector((state: StoreDto) => state.sendTemplate);

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
          <EmailSelectSend
            items={selectSendFirstItems}
            value={templateType}
            onChange={(item) => dispatch(setSendTemplateType(item))}
          />
        </Grid>
        <Grid>
          <EmailGroupSelect
            onClick={(item) => dispatch(setSendTemplate(item))}
            value={sendTemplate}
            type="send"
            items={switchSendTemplateType(templateType.value)}
          />
        </Grid>
      </Grid>
    </EmailDialogHeader>
  );
};
