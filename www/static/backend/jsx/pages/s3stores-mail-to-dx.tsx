import ReactDOM from "react-dom";
import React from "react";
import { emailStore } from "@redux/stores";
import { Provider } from "react-redux";
import { MailRouters } from "@s3stores-mail/routers";
import { SnackBar } from "@admin/modules/shared/components/snack-bar/SnackBar";
import { BrowserRouter } from "react-router-dom";
import { EmailRouterContext } from "@s3stores-mail/contexts/email-router-context/EmailRouter.context";

(() => {
  const elem: HTMLElement = document.querySelector(".dx-mails");

  if (!elem) return;

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
        <SnackBar>
          <BrowserRouter>
            <MailRouters distributorId={elem?.dataset?.id} />
          </BrowserRouter>
        </SnackBar>
      </EmailRouterContext.Provider>
    </Provider>,
    elem
  );
})();
