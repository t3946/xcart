import React from "react";
import LoginButtonMobile from "@client/jsx/modules/account/components/hat/LoginButtonMobile";
import LoginButtonTablet from "@client/jsx/modules/account/components/hat/LoginButtonTablet";

const LoginButton: React.FC = () => {
  return (
    <React.Fragment>
      <LoginButtonMobile />
      <LoginButtonTablet />
    </React.Fragment>
  );
};

export default LoginButton;
