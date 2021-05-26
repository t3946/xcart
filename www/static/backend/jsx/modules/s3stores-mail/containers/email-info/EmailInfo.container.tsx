import React, { useContext } from "react";
import { Paper } from "@material-ui/core";
import { EmailInfoBodyData } from "@s3stores-mail/components/simple/email-info-body-data/EmailInfoBodyData";
import { EmailInfoDataFooter } from "@s3stores-mail/components/smart/email-info-data-footer/EmailInfoDataFooter";
import { EmailInfoHeader } from "@s3stores-mail/components/ordinary/email-info-header/EmailInfoHeader";
import { useDispatch } from "react-redux";
import {
  editSendData,
  setSendTemplate,
  setSendTemplateType,
} from "@redux/actions";
import { selectSendFirstItems } from "@s3stores-mail/ts/consts";
import { EmailSendDialogContext } from "@s3stores-mail/contexts";
import { SelectItemDto } from "@s3stores-mail/ts/types";

export const EmailInfoContainer = () => {
  const dialog = useContext(EmailSendDialogContext);
  const dispatch = useDispatch();

  const handleReply = () => {
    dispatch(editSendData("<p>1123123</p>", "replyText"));
    dialog.handleClickOpen();
  };

  const handleForward = () => {
    dialog.handleClickOpen();
  };

  const handleClick = (item: SelectItemDto) => {
    dispatch(setSendTemplate(item));
    dispatch(setSendTemplateType(selectSendFirstItems[1]));
    dialog.handleClickOpen();
  };

  return (
    <React.Fragment>
      <EmailInfoHeader />
      <Paper elevation={0} square={true} className="email-info-data-wrapper">
        <EmailInfoBodyData />
        <EmailInfoDataFooter
          handleReply={handleReply}
          handleClick={handleClick}
          handleForward={handleForward}
        />
      </Paper>
    </React.Fragment>
  );
};
