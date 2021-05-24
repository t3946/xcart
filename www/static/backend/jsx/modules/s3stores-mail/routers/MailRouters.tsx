import React from "react";
import { BrowserRouter, Switch, Route } from "react-router-dom";
import { Email } from "../components/email/Email";
import { EmailListSearch } from "../components/email-list-search/EmailListSearch";
import { EmailInfo } from "../components/email-info/EmailInfo";
import { EmailSendDialogHOC } from "@s3stores-mail/hoc";

export const MailRouters: React.FC = () => {
  return (
    <div>
      <EmailListSearch />
      <BrowserRouter>
        <Switch>
          <Route
            exact
            path="/admin/forms/email-dashboard/page/:page"
            component={EmailSendDialogHOC(<Email />)}
          />
          <Route
            path="/admin/forms/email-dashboard/email-info/:id"
            component={EmailSendDialogHOC(<EmailInfo />)}
          />
        </Switch>
      </BrowserRouter>
    </div>
  );
};
