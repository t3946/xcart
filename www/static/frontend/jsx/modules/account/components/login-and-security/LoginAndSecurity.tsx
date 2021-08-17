import React, { useContext } from "react";
import { Redirect } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";

const LoginAndSecurity = (): any => {
  const user = useSelector((e: StoreDto) => e.user);
  const { showSnackbar } = useContext(SnackbarContext);

  showSnackbar({
    header: "Success",
    message: "You have successfully modified your account!",
    theme: "wrong",
  });

  return (
    <>
      <div>LoginAndSecurity</div>
      {!user && <Redirect to={route("account:login")} />}
    </>
  );
};

export default LoginAndSecurity;
