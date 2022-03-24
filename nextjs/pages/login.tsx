import * as React from "react";
import Page from "@modules/account/components/layout/Page";
import LoginForm from "@modules/account/components/authorization/LoginForm";
import Head from "next/head";

function Login() {
  return (
    <Page showBreadcrumbs={false}>
      <Head>
        <title>Login</title>
      </Head>
      <div className="d-flex justify-content-center">
        <LoginForm />
      </div>
    </Page>
  );
}

export default Login;
