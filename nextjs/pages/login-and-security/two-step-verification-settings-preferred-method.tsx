import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import TSVChangePreferredMethod from "@modules/account/components/login-and-security/TSVChangePreferredMethod";

const TSVChangePreferredMethodPage: React.FC = function () {
  return (
    <PageTwoColumns>
      <TSVChangePreferredMethod />
    </PageTwoColumns>
  );
};

export default TSVChangePreferredMethodPage;
