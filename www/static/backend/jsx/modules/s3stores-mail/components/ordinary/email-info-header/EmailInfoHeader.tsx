import React, { useContext, useState } from "react";
import { Grid, Paper } from "@material-ui/core";
import { ReadedSwitch } from "@s3stores-mail/components/simple/readed-switch/ReadedSwitch";
import { EmailInfoHeaderIcons } from "@s3stores-mail/components/ordinary/email-info-header-icons/EmailInfoHeaderIcons";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";
import { EmailInfoLabels } from "@s3stores-mail/components/ordinary/email-info-labels/EmailInfoLabels";
import { EmailMenuLabel } from "@s3stores-mail/components/ordinary/email-menu-label/EmailMenuLabel";
import { EmailCreateLabel } from "@s3stores-mail/components/smart/email-create-label/EmailCreateLabel";
import { EmailLabelContext } from "@s3stores-mail/contexts/email-label-context/EmailLabelContext";
import { EmailThreadContext } from "@s3stores-mail/contexts/email-thread-context/EmailThread.context";

export const EmailInfoHeader: React.FC<any> = () => {
  const context = useContext(EmailInfoContext);
  const { emailInfo } = useContext(EmailThreadContext);
  const { labels } = useContext(EmailInfoContext);
  const [menu, setMenu] = useState(false);
  const [modal, setModal] = useState(false);

  const onClickMenu = () => {
    setMenu(!menu);
  };

  return (
    <Paper className="header-wrap info" square={true}>
      <EmailLabelContext.Provider
        value={{
          modal: { get: modal, set: () => setModal(!modal) },
          messageId: emailInfo.message_id,
        }}
      >
        <Grid
          className="email-header-wrapper"
          container
          justify="space-between"
          alignItems="center"
        >
          <div className="subject-mail-wrapper">
            <div className="subject-email-block-personal">
              <span
                style={{
                  fontSize: 15,
                }}
              >
                {emailInfo.subject}
              </span>
            </div>
            <div className="labels-list-mail">
              <EmailInfoLabels labelsList={emailInfo.labels} />
            </div>
            {emailInfo.contains_action && (
              <ReadedSwitch
                inHeader={true}
                actionName={emailInfo.action.name}
                editAction={() => context.editAction(emailInfo)}
                readed={emailInfo.action.action}
              />
            )}
          </div>
          <Grid>
            <EmailInfoHeaderIcons addLabel={{ get: menu, set: onClickMenu }} />
          </Grid>
          {menu && (
            <EmailMenuLabel
              labelMailList={emailInfo.labels}
              labelList={labels}
            />
          )}
          <EmailCreateLabel
            state={{ get: modal, set: () => setModal(!modal) }}
          />
        </Grid>
      </EmailLabelContext.Provider>
    </Paper>
  );
};
