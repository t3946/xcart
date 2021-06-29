import ReactDOM from "react-dom";
import React from "react";
import { emailStore } from "@redux/stores";
import { Provider } from "react-redux";
import { MailRouters } from "@s3stores-mail/routers";
import { EmailSnackbar } from "@s3stores-mail/containers/email-snackbar/EmailSnackbar";
import { BrowserRouter } from "react-router-dom";
import { EmailRouterContext } from "../modules/s3stores-mail/contexts/email-router-context/EmailRouter.context";

(() => {
  const elem = document.getElementsByClassName("email-dashboard");

  if (!elem[0]) return;

  const listRouter = "/admin/forms/email-dashboard/page/";

  const infoRouter = "/admin/email-dashboard/email-info/";

  ReactDOM.render(
    <Provider store={emailStore as any}>
      <EmailRouterContext.Provider
        value={{
          listRouter,
          infoRouter,
        }}
      >
        <EmailSnackbar>
          <BrowserRouter>
            <MailRouters />
          </BrowserRouter>
        </EmailSnackbar>
      </EmailRouterContext.Provider>
    </Provider>,
    elem[0]
  );
})();
