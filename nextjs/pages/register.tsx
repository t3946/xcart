import * as React from "react";
import Page from "@modules/account/components/layout/Page";
import RegisterForm from "@modules/account/components/authorization/RegisterForm";

function Login() {
  return (
    <Page showBreadcrumbs={false}>
      <div className="d-flex align-items-center">
        <RegisterForm />
      </div>
    </Page>
  );
}

export default Login;
