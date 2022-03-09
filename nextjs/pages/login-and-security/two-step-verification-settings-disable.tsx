import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import TSVDisable from "@modules/account/components/login-and-security/TSVDisable";

const TSVDisablePage: React.FC = function () {
  return (
    <PageTwoColumns>
      <TSVDisable />
    </PageTwoColumns>
  );
};

export default TSVDisablePage;
