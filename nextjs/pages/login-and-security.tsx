import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import LoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

function LoginAndSecurityPage() {
  const user = useSelectorAccount((e) => e.user);

  return <PageTwoColumns>{user && <LoginAndSecurity />}</PageTwoColumns>;
}

export default LoginAndSecurityPage;
