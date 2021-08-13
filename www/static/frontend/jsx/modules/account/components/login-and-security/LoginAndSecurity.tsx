import React from "react";
import { Redirect } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";

const LoginAndSecurity = (): any => {
  const user = useSelector((e: StoreDto) => e.user);

  return (
    <>
      <div>LoginAndSecurity</div>
      {!user && <Redirect to={route("account:login")} />}
    </>
  );
};

export default LoginAndSecurity;
