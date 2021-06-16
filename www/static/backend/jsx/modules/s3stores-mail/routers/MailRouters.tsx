import React, { useContext } from "react";
import { Switch, Route, useLocation } from "react-router-dom";
import { Email } from "../pages/email/Email";
import {
  editSearchOptions,
  getTemplates,
  resetSendData,
  setSearchOptions,
} from "@redux/actions";
import { useDispatch } from "react-redux";
import { EmailDialogHOC } from "@s3stores-mail/hoc/email-dialog/EmailDialogHOC";
import { EmailSend } from "@s3stores-mail/containers/email-send/EmailSend";
import { EmailInfoPage } from "@s3stores-mail/pages/email-info/EmailInfoPage";
import { EmailSearchDialog } from "@s3stores-mail/containers/email-search-dialog.tsx/EmailSearchDialog";
import EmailSearchContainer from "@s3stores-mail/containers/email-search/EmailSearch.container";
import { EmailRouterContext } from "@s3stores-mail/contexts/email-router-context/EmailRouter.context";
import { initialValues } from "@s3stores-mail/ts/consts";
import { emailStore } from "../../../redux/stores";

export const MailRouters: React.FC<any> = ({ distributorId }) => {
  const dispatch = useDispatch();
  const onClose = () => {
    return dispatch(resetSendData());
  };
  dispatch(getTemplates());

  dispatch(
    editSearchOptions({ ...emailStore.getState().searchOptions, distributorId })
  );

  const location = useLocation();

  const routers = useContext(EmailRouterContext);

  console.log(location);

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
          path={`${routers.listRouter}:page`}
          component={EmailDialogHOC(<Email />, <EmailSend />, onClose)}
        />
        <Route
          path={`${routers.infoRouter}:id`}
          component={EmailDialogHOC(<EmailInfoPage />, <EmailSend />, onClose)}
        />
      </Switch>
    </div>
  );
};
