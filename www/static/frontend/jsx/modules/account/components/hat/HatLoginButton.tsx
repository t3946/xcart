import React from "react";
import MobileLoginButton from "./MobileLoginButton";
import TabletLoginButton from "./TabletLoginButton";

const LoginButton = (props) => {
  return (
    <React.Fragment>
      <MobileLoginButton />
      <TabletLoginButton />
    </React.Fragment>
  );
};

export default LoginButton;
