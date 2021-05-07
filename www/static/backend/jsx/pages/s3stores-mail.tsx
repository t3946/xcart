import ReactDOM from "react-dom";
import React from "react";
import { emailStore } from "../redux/stores/emailStore";
import { Provider } from "react-redux";
import EmailWrap from "../modules/s3stores-mail/wrap/EmailWrap";
import { EmailListHeaderContainer } from "../modules/s3stores-mail/containers/email-list-header/EmailListHeader.container";
import { Email } from "../modules/s3stores-mail/components/email/Email";
import { MailRouters } from "../modules/s3stores-mail/routers/MailRouters";

(() => {
  const elem = document.getElementsByClassName("email-dashboard");

  ReactDOM.render(
    <Provider store={emailStore}>
      <MailRouters />
    </Provider>,
    elem[0]
  );
})();
