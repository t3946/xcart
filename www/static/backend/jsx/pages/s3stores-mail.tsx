import ReactDOM from "react-dom";
import React from "react";
import { emailStore } from "../redux/stores";
import { Provider } from "react-redux";
import { MailRouters } from "../modules/s3stores-mail/routers";

(() => {
  const elem = document.getElementsByClassName("email-dashboard");

  ReactDOM.render(
    <Provider store={emailStore}>
      <MailRouters />
    </Provider>,
    elem[0]
  );
})();
