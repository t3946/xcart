import React, { useContext, useState } from "react";
import { Grid, Paper } from "@material-ui/core";
import { ReadedSwitch } from "@s3stores-mail/components/simple/readed-switch/ReadedSwitch";
import { EmailInfoHeaderIcons } from "@s3stores-mail/components/ordinary/email-info-header-icons/EmailInfoHeaderIcons";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";
import { EmailInfoLabels } from "@s3stores-mail/components/ordinary/email-info-labels/EmailInfoLabels";
import { useDispatch } from "react-redux";
import { removeLabelEmail } from "@redux/actions";
import { EmailMenuLabel } from "@s3stores-mail/components/ordinary/email-menu-label/EmailMenuLabel";
import { EmailCreateLabel } from "@s3stores-mail/components/smart/email-create-label/EmailCreateLabel";
import { EmailLabelContext } from "@s3stores-mail/contexts/email-label-context/EmailLabelContext";

export const EmailInfoHeader: React.FC<any> = ({ info }) => {
  const context = useContext(EmailInfoContext);
  const dispatch = useDispatch();
  const onDeleteLabel = (id: string) => {
    dispatch(removeLabelEmail(info.message_id, id));
  };
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
          messageId: info.message_id,
        }}
      >
        <Grid container justify="space-around" alignItems="center">
          <div className="subject-mail-wrapper">
            <div className="subject-email-block">
              <span
                style={{
                  fontSize: 15,
                }}
              >
                {info.subject}
              </span>
            </div>
            <div className="labels-list-mail">
              <EmailInfoLabels
                labelDelete={onDeleteLabel}
                labelsList={info.labels}
              />
            </div>
          </div>
          <Grid>
            {info.contains_action && (
              <ReadedSwitch
                inHeader={true}
                actionName={info.action.name}
                editAction={context.editAction}
                readed={info.action.action}
              />
            )}
          </Grid>
          <Grid>
            <EmailInfoHeaderIcons addLabel={{ get: menu, set: onClickMenu }} />
          </Grid>
          {menu && (
            <EmailMenuLabel labelMailList={info.labels} labelList={labels} />
          )}
          <EmailCreateLabel
            state={{ get: modal, set: () => setModal(!modal) }}
          />
        </Grid>
      </EmailLabelContext.Provider>
    </Paper>
  );
};
