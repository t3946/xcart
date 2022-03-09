import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import TSVRecovery from "@modules/account/components/login-and-security/TSVRecovery";

const TSVRecoveryPage: React.FC = function () {
  return (
    <PageTwoColumns>
      <TSVRecovery />
    </PageTwoColumns>
  );
};

export default TSVRecoveryPage;
