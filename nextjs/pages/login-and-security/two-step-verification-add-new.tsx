import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import TSVAddNewApp from "@modules/account/components/login-and-security/TSVAddNewApp";

const TSVAddNewAppPage: React.FC = function () {
  return (
    <PageTwoColumns>
      <TSVAddNewApp />
    </PageTwoColumns>
  );
};

export default TSVAddNewAppPage;
