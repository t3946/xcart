import ReactDOM from "react-dom";
import React from "react";
import { emailStore } from "@redux/stores";
import { Provider } from "react-redux";
import { MailRouters } from "@s3stores-mail/routers";
import { SnackBar } from "@admin/modules/shared/components/snack-bar/SnackBar";
import { BrowserRouter } from "react-router-dom";
import { EmailRouterContext } from "../modules/s3stores-mail/contexts/email-router-context/EmailRouter.context";

(() => {
  const elem = document.getElementsByClassName("email-dashboard");

  if (!elem[0]) return;

  const listRouter = "/admin/forms/email-dashboard/page/";

  const infoRouter = "/admin/forms/email-dashboard/email-info/";

  ReactDOM.render(
    <Provider store={emailStore as any}>
      <EmailRouterContext.Provider
        value={{
          listRouter,
          infoRouter,
        }}
      >
        <SnackBar>
          <BrowserRouter>
            <MailRouters />
          </BrowserRouter>
        </SnackBar>
      </EmailRouterContext.Provider>
    </Provider>,
    elem[0]
  );
})();
