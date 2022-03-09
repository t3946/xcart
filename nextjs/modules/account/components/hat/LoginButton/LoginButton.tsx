import React from "react";
import LoginButtonMobile from "@modules/account/components/hat/LoginButton/LoginButtonMobile";
import LoginButtonTablet from "@modules/account/components/hat/LoginButton/LoginButtonTablet";

const LoginButton: React.FC = () => {
  return (
    <React.Fragment>
      <LoginButtonMobile />
      <LoginButtonTablet />
    </React.Fragment>
  );
};

export default LoginButton;
