import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import SecureYourAccount from "@modules/account/components/login-and-security/SecureYourAccount";

const SecureYourAccountPage: React.FC = function () {
  return (
    <PageTwoColumns>
      <SecureYourAccount />
    </PageTwoColumns>
  );
};

export default SecureYourAccountPage;
