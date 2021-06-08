import React, { useEffect } from "react";
import {
  BrowserRouter,
  Switch,
  Route,
  useParams,
  withRouter,
} from "react-router-dom";
import { Email } from "../pages/email/Email";
import {
  getPage,
  getTemplates,
  resetSendData,
  setLoading,
} from "@redux/actions";
import { useDispatch, useSelector } from "react-redux";
import { EmailDialogHOC } from "@s3stores-mail/hoc/email-dialog/EmailDialogHOC";
import { EmailSend } from "@s3stores-mail/containers/email-send/EmailSend";
import { EmailInfoPage } from "@s3stores-mail/pages/email-info/EmailInfoPage";
import { EmailSearchDialog } from "@s3stores-mail/containers/email-search-dialog.tsx/EmailSearchDialog";
import EmailSearchContainer from "@s3stores-mail/containers/email-search/EmailSearch.container";
import { StoreDto } from "../ts/types";

export const MailRouters: React.FC = () => {
  const dispatch = useDispatch();
  const onClose = () => {
    return dispatch(resetSendData());
  };
  dispatch(getTemplates());

  const Search = EmailDialogHOC(
    <EmailSearchContainer />,
    <EmailSearchDialog />
  );
  return (
    <div>
      <Search />
      <Switch>
        <Route
          exact
          path="/admin/forms/email-dashboard/page/:page"
          component={EmailDialogHOC(<Email />, <EmailSend />, onClose)}
        />
        <Route
          path="/admin/forms/email-dashboard/email-info/:id"
          component={EmailDialogHOC(<EmailInfoPage />, <EmailSend />, onClose)}
        />
      </Switch>
    </div>
  );
};
