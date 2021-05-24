import React, { useContext } from "react";
import { Button, Grid } from "@material-ui/core";
import ReplyIcon from "@material-ui/icons/Reply";
import {
  selectInfoItems,
  selectSendFirstItems,
} from "@s3stores-mail/ts/consts";
import ForwardIcon from "@material-ui/icons/Forward";
import { EmailSendDialogContext } from "@s3stores-mail/contexts";
import { useDispatch, useSelector } from "react-redux";
import {
  editSendData,
  setSendTemplate,
  setSendTemplateType,
} from "@redux/actions";
import { EmailGroupSelect } from "@s3stores-mail/components/email-group-select/EmailGroupSelect";
import { StoreDto } from "@s3stores-mail/ts/types";

interface SelectItemDto {
  value: string;
  viewValue: string;
}
export const EmailInfoDataFooter: React.FC = () => {
  const dialog = useContext(EmailSendDialogContext);
  const dispatch = useDispatch();

  const handleReply = () => {
    dispatch(editSendData("<p>1123123</p>", "replyText"));
    dialog.handleClickOpen();
  };

  const handleClick = (item: SelectItemDto) => {
    dispatch(setSendTemplate(item));
    dispatch(setSendTemplateType(selectSendFirstItems[1]));
    dialog.handleClickOpen();
  };

  const sendTemplate = useSelector((state: StoreDto) => state.sendTemplate);
  return (
    <Grid container alignItems="center" className="email-info-footer">
      <Grid xs={2}>
        <Button
          onClick={handleReply}
          className="email-info-btn"
          variant="outlined"
        >
          <ReplyIcon className="email-info-btn-icon-reply" />
          <span>REPLY</span>
        </Button>
      </Grid>
      <Grid container alignItems="center" xs={3}>
        <EmailGroupSelect
          value={sendTemplate}
          onClick={handleClick}
          type="info"
          items={selectInfoItems}
        />
      </Grid>
      <Grid container alignItems="center" xs={7} justify="flex-end">
        <Button
          onClick={dialog.handleClickOpen}
          className="email-info-btn"
          variant="outlined"
        >
          <ForwardIcon className="email-info-btn-icon" />
          <span>FORWARD</span>
        </Button>
      </Grid>
    </Grid>
  );
};
