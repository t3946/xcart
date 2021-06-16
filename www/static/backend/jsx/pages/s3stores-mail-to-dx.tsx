import ReactDOM from "react-dom";
import React from "react";
import { emailStore } from "@redux/stores";
import { Provider } from "react-redux";
import { MailRouters } from "@s3stores-mail/routers";
import { EmailSnackbar } from "@s3stores-mail/containers/email-snackbar/EmailSnackbar";
import { BrowserRouter } from "react-router-dom";
import { EmailRouterContext } from "@s3stores-mail/contexts/email-router-context/EmailRouter.context";

(() => {
  const elem: HTMLElement = document.querySelector(".admin-page");

  console.log(elem);

  const listRouter = "/admin/distributor/253/50/email-dashboard/page/";

  const infoRouter = "/admin/distributor/253/50/email-dashboard/email/";

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
            <MailRouters distributorId={elem?.dataset?.id} />
          </BrowserRouter>
        </EmailSnackbar>
      </EmailRouterContext.Provider>
    </Provider>,
    elem
  );
})();
