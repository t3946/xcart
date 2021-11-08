import React from "react";
import { Button, Grid } from "@material-ui/core";
import ReplyIcon from "@material-ui/icons/Reply";
import ForwardIcon from "@material-ui/icons/Forward";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { EmailGroupSelect } from "@s3stores-mail/components/smart/email-group-select/EmailGroupSelect";

export const sEmailInfoDataFooter: React.FC<any> = ({
  handleReply,
  handleClick,
  handleForward,
  templates,
}) => {
  const sendTemplate = useSelector((state: StoreDto) => state.sendTemplate);
  return (
    <Grid container alignItems="center" className="email-info-footer">
      <Grid xs={2}>
        <Button
          onClick={handleReply}
          className="email-info-btn"
          variant="outlined"
        >
          <img
            src="/static/backend/dist/images/icons/arrow-reply.svg"
            className="email-info-btn-icon-reply"
          />
          <span>REPLY</span>
        </Button>
      </Grid>
      <Grid container alignItems="center" xs={4}>
        <EmailGroupSelect
          label={"REPLY BY TEMPLATE"}
          value={sendTemplate}
          onClick={handleClick}
          type="info"
          items={templates.items}
        />
      </Grid>
      <Grid container alignItems="center" xs={6} justify="flex-end">
        <Button
          onClick={handleForward}
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
