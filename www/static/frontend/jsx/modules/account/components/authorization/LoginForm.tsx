import React from "react";
import { StoreDto } from "@s3stores-mail/ts/types";
import { useSelector } from "react-redux";
import LoginFormInputLogin from "./LoginFormInputLogin";
import LoginFormInputPassword from "./LoginFormInputPassword";
import { useHistory } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import classNames from "classnames";
import { noSidebarClasses } from "@client/modules/account/ts/consts/no-sidebar-classes";

const LoginForm: React.FC<any> = () => {
  const user = useSelector((e: StoreDto) => e.user);

  user && useHistory().push(route("account:index"));

  const INPUT_LOGIN_MODE = 0;
  const INPUT_PASSWORD_MODE = 1;
  const [mode, setMode] = React.useState(INPUT_LOGIN_MODE);
  const [userLogin, setUserLogin] = React.useState("");

  function onChangeLogin(login) {
    setUserLogin(login);
  }

  function formTemplate() {
    if (mode === INPUT_LOGIN_MODE) {
      return (
        <LoginFormInputLogin
          userLogin={userLogin}
          goToPasswordInput={goToPasswordInput}
          onChangeLogin={onChangeLogin}
        />
      );
    } else {
      return (
        <LoginFormInputPassword
          userLogin={userLogin}
          goToInputLogin={goToInputLogin}
        />
      );
    }
  }

  function goToInputLogin() {
    setMode(INPUT_LOGIN_MODE);
  }

  function goToPasswordInput() {
    setMode(INPUT_PASSWORD_MODE);
  }

  return (
    <div className={classNames(noSidebarClasses)}>
      <div className="account-auth-form account_auth-form">
        <h1 className="account-form-header">Sign-In</h1>
        {formTemplate()}
      </div>
    </div>
  );
};

export default LoginForm;
