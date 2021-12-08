import * as React from "react";
import Link from "next/link";
import getInitialState from "@services/axios/Account";

function Register() {
  return (
    <div>
      <h1>Register</h1>

      <Link href="/login">
        <a>static link login</a>
      </Link>
    </div>
  );
}

export default Register;
