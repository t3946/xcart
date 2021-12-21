import React from "react";
import LoginInputForm from "@modules/account/components/password-assistance/LoginInputForm";
import OneTimePasswordInputForm from "@modules/account/components/password-assistance/OneTimePasswordInputForm";
import ChangePasswordForm from "@modules/account/components/password-assistance/ChangePasswordForm";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import { useRouter } from "next/router";
const PasswordAssistance: React.FC<any> = function () {
  const INPUT_LOGIN_MODE = 0;
  const INPUT_OTP_MODE = 1;
  const CHANGE_PASSWORD_MODE = 2;
  const [mode, setMode] = React.useState(INPUT_LOGIN_MODE);
  const [login, setLogin] = React.useState("");
  const [oneTimePassword, setOneTimePassword] = React.useState();
  const [resetPasswordToken, setResetPasswordToken] = React.useState();
  const user = useSelector((e: StoreInterface) => e.user);

  user && useRouter().push("/dashboard");

  function goToOTPInput(login) {
    setLogin(login);
    setMode(INPUT_OTP_MODE);
  }

  function goToResetPassword(resetPasswordToken) {
    setResetPasswordToken(resetPasswordToken);
    setMode(CHANGE_PASSWORD_MODE);
  }

  function oneTimePasswordChanged(oneTimePassword) {
    setOneTimePassword({ ...oneTimePassword });
  }

  function fromTemplate() {
    switch (mode) {
      case INPUT_LOGIN_MODE:
        return (
          <LoginInputForm
            goToOTPInput={goToOTPInput}
            oneTimePasswordChanged={oneTimePasswordChanged}
          />
        );
      case INPUT_OTP_MODE:
        return (
          <OneTimePasswordInputForm
            login={login}
            goToLoginInput={() => setMode(INPUT_LOGIN_MODE)}
            goToResetPassword={goToResetPassword}
            oneTimePassword={oneTimePassword}
            oneTimePasswordChanged={oneTimePasswordChanged}
          />
        );
      case CHANGE_PASSWORD_MODE:
        return (
          <ChangePasswordForm
            goToLoginInput={() => setMode(INPUT_LOGIN_MODE)}
            resetPasswordToken={resetPasswordToken}
          />
        );
    }
  }

  function footerTemplate() {
    if (mode === CHANGE_PASSWORD_MODE) {
      return (
        <div className="account-auth-page-footer">
          <h6>Secure password tips:</h6>

          <ul className={"auth-form-info"}>
            <li>
              Use at least 8 characters, a combination of numbers and letters is
              best.
            </li>
            <li>
              Do not use the same password you have used with us previously.
            </li>
            <li>
              Do not use dictionary words, your name, e-mail address, mobile
              phone number or other personal information that can be easily
              obtained.
            </li>
            <li>Do not use the same password for multiple online accounts.</li>
          </ul>
        </div>
      );
    }
  }

  return (
    <div className={"account-auth-form-container"}>
      <div className="account-auth-form account_auth-form">
        {fromTemplate()}
      </div>

      {footerTemplate()}
    </div>
  );
};

export default PasswordAssistance;
