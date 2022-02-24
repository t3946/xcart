import * as React from "react";
import Page from "@modules/account/components/layout/Page";
import PasswordAssistance from "@modules/account/components/password-assistance/PasswordAssistance";

function Login() {
  return (
    <Page showBreadcrumbs={false}>
      <div className="d-flex justify-content-center">
        <PasswordAssistance />
      </div>
    </Page>
  );
}

export default Login;
