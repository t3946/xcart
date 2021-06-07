import ReactDOM from "react-dom";
import React from "react";
import { emailStore } from "@redux/stores";
import { Provider } from "react-redux";
import { MailRouters } from "@s3stores-mail/routers";
import { EmailSnackbar } from "@s3stores-mail/containers/email-snackbar/EmailSnackbar";

(() => {
  const elem = document.getElementsByClassName("email-dashboard");

  ReactDOM.render(
    <Provider store={emailStore as any}>
      <EmailSnackbar>
        <MailRouters />
      </EmailSnackbar>
    </Provider>,
    elem[0]
  );
})();
