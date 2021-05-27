import React, { useEffect } from "react";
import { BrowserRouter, Switch, Route, useLocation } from "react-router-dom";
import { Email } from "../pages/email/Email";
import { EmailListSearch } from "@s3stores-mail/components/smart/email-list-search/EmailListSearch";
import { resetSendData } from "@redux/actions";
import { useDispatch } from "react-redux";
import { EmailDialogHOC } from "@s3stores-mail/hoc/email-dialog/EmailDialogHOC";
import { EmailSend } from "@s3stores-mail/containers/email-send/EmailSend";
import { EmailInfoPage } from "@s3stores-mail/pages/email-info/EmailInfoPage";

export const MailRouters: React.FC = () => {
  const dispatch = useDispatch();
  const onClose = () => {
    return dispatch(resetSendData());
  };
  return (
    <div>
      <EmailListSearch />
      <BrowserRouter>
        <Switch>
          <Route
            exact
            path="/admin/forms/email-dashboard/page/:page"
            component={EmailDialogHOC(<Email />, <EmailSend />, onClose)}
          />
          <Route
            path="/admin/forms/email-dashboard/email-info/:id"
            component={EmailDialogHOC(
              <EmailInfoPage />,
              <EmailSend />,
              onClose
            )}
          />
        </Switch>
      </BrowserRouter>
    </div>
  );
};
