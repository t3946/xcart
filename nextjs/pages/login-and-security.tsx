import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import LoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity";

function LoginAndSecurityPage() {
  return (
    <PageTwoColumns>
      <LoginAndSecurity />
    </PageTwoColumns>
  );
}

export default LoginAndSecurityPage;
