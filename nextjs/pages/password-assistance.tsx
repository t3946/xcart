import * as React from "react";
import Page from "@modules/account/components/layout/Page";
import PasswordAssistance from "@modules/account/components/password-assistance/PasswordAssistance";
import Head from "next/head";

function Login() {
  return (
    <Page showBreadcrumbs={false}>
      <Head>
        <title>Password assistance</title>
      </Head>
      <div className="d-flex justify-content-center">
        <PasswordAssistance />
      </div>
    </Page>
  );
}

export default Login;
