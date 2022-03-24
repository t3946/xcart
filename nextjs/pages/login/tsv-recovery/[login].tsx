import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import TSVRecovery from "@modules/account/components/login-and-security/TSVRecovery";
import Head from "next/head";

const TSVRecoveryPage: React.FC = function () {
  return (
    <PageTwoColumns>
      <Head>
        <title>TSV Recovery</title>
      </Head>

      <TSVRecovery />
    </PageTwoColumns>
  );
};

export default TSVRecoveryPage;
