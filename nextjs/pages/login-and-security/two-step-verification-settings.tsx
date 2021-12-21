import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import TSVSettings from "@modules/account/components/login-and-security/TSVSettings";

const TSVSettingsPage: React.FC = function () {
  return (
    <PageTwoColumns>
      <TSVSettings />
    </PageTwoColumns>
  );
};

export default TSVSettingsPage;
