import * as React from "react";
import Page from "@modules/account/components/layout/Page";
import LoginForm from "@modules/account/components/authorization/LoginForm";

function Login() {
  return (
    <Page showBreadcrumbs={false}>
      <div className="d-flex justify-content-center">
        <LoginForm />
      </div>
    </Page>
  );
}

export default Login;
