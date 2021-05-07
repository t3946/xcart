import React from "react";
import { BrowserRouter, Switch, Route } from "react-router-dom";
import { Email } from "../components/email/Email";
import { EmailListSearch } from "../components/email-list-search/EmailListSearch";
import { EmailInfo } from "../components/email-info/EmailInfo";

export const MailRouters = () => {
  return (
    <>
      <EmailListSearch />
      <BrowserRouter>
        <Switch>
          <Route exact path="/admin/forms/email-dashboard/" component={Email} />
          <Route
            path="/admin/forms/email-dashboard/email-info/:id"
            component={EmailInfo}
          />
        </Switch>
      </BrowserRouter>
    </>
  );
};
