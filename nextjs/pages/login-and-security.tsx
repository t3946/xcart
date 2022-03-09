import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import LoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";

function LoginAndSecurityPage() {
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);

  React.useEffect(() => {
    if (!user) {
      router.push("/login");
    }
  });

  return <PageTwoColumns>{user && <LoginAndSecurity />}</PageTwoColumns>;
}

export default LoginAndSecurityPage;
