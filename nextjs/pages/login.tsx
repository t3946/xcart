import * as React from "react";
import Link from "next/link";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

function Login() {
  const cart = useSelectorAccount((e) => e.cart);

  return (
    <div>
      login
      <Link href="/register">
        <a>static link register</a>
      </Link>
    </div>
  );
}

export default Login;
