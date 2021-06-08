import React, { useContext } from "react";
import { Grid, Paper } from "@material-ui/core";
import { ReadedSwitch } from "@s3stores-mail/components/simple/readed-switch/ReadedSwitch";
import { EmailInfoHeaderIcons } from "@s3stores-mail/components/ordinary/email-info-header-icons/EmailInfoHeaderIcons";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";

export const EmailInfoHeader: React.FC<any> = ({ info }) => {
  const context = useContext(EmailInfoContext);
  return (
    <Paper className="header-wrap info" square={true}>
      <Grid container justify="space-around" alignItems="center">
        <Grid xs={6}>
          <span
            style={{
              fontSize: 15,
            }}
          >
            {info.subject}
          </span>
        </Grid>
        <Grid>
          <ReadedSwitch
            inHeader={true}
            actionName={info.action.name}
            editAction={context.editAction}
            readed={info.action.action}
          />
        </Grid>
        <Grid>
          <EmailInfoHeaderIcons />
        </Grid>
      </Grid>
    </Paper>
  );
};
