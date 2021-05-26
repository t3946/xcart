import React from "react";
import { BrowserRouter, Switch, Route } from "react-router-dom";
import { Email } from "../pages/email/Email";
import { EmailInfo } from "../pages/email-info/EmailInfo";
import { EmailSendDialogHOC } from "@s3stores-mail/hoc";
import { EmailListSearch } from "@s3stores-mail/components/smart/email-list-search/EmailListSearch";

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
