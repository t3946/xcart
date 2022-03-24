import * as React from "react";
import Page from "@modules/account/components/layout/Page";
import RegisterForm from "@modules/account/components/authorization/RegisterForm";
import Head from "next/head";

function Login() {
  return (
    <Page showBreadcrumbs={false}>
      <Head>
        <title>Register</title>
      </Head>
      <div className="d-flex align-items-center">
        <RegisterForm />
      </div>
    </Page>
  );
}

export default Login;
