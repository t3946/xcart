import React from "react";
import LoginInputForm from "@modules/account/components/password-assistance/LoginInputForm";
import OneTimePasswordInputForm from "@modules/account/components/password-assistance/OneTimePasswordInputForm";
import ChangePasswordForm from "@modules/account/components/password-assistance/ChangePasswordForm";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import { useRouter } from "next/router";
import Link from "next/link";
import cn from "classnames";

import StylesLoginForm from "@modules/account/components/authorization/LoginForm.module.scss";
import StylesChangePasswordForm from "@modules/account/components/password-assistance/ChangePasswordForm.module.scss";
import Styles from "@modules/account/components/password-assistance/PasswordAssistance.module.scss";

const PasswordAssistance: React.FC<any> = function () {
  const INPUT_LOGIN_MODE = 0;
  const INPUT_OTP_MODE = 1;
  const CHANGE_PASSWORD_MODE = 2;
  const router = useRouter();
  const [mode, setMode] = React.useState(INPUT_LOGIN_MODE);
  const [login, setLogin] = React.useState("");
  const [oneTimePassword, setOneTimePassword] =
    React.useState<Record<any, any>>();
  const [resetPasswordToken, setResetPasswordToken] = React.useState("");
  const user = useSelector((e: StoreInterface) => e.user);

  React.useEffect(() => {
    user && router.push("/dashboard");
  }, []);

  function goToOTPInput(login: string) {
    setLogin(login);
    setMode(INPUT_OTP_MODE);
  }

  function goToResetPassword(resetPasswordToken: string) {
    setResetPasswordToken(resetPasswordToken);
    setMode(CHANGE_PASSWORD_MODE);
  }

  function oneTimePasswordChanged(oneTimePassword: Record<any, any>) {
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
            login={login}
            goToLoginInput={() => setMode(INPUT_LOGIN_MODE)}
            resetPasswordToken={resetPasswordToken}
          />
        );
    }
  }

  function footerTemplate() {
    switch (mode) {
      case INPUT_LOGIN_MODE:
        return (
          <div className={Styles.footerBorder}>
            <div
              className={cn(
                "d-sm-none",
                "d-lg-block",
                "account-auth-page-footer",
                "pt-3",
                "pt-lg-3",
                "mt-sm-0"
              )}
            >
              <div className={cn(Styles.footerTitle, "mb-2")}>
                Has your email or mobile number changed?
              </div>
              <p className={Styles.footerText}>
                If you no longer use the email address associated with your S3
                Stores account, you may contact{" "}
                <Link href="#">
                  <a className="d-inline-block">Help Center</a>
                </Link>{" "}
                Service for help restoring access to your account.
              </p>
            </div>
          </div>
        );
      case CHANGE_PASSWORD_MODE:
        return (
          <div className={StylesChangePasswordForm.footer}>
            <div className={StylesChangePasswordForm.footerTitle}>
              Secure Password Tips:
            </div>

            <ul
              className={cn(
                "auth-form-info",
                StylesChangePasswordForm.footerText,
                StylesChangePasswordForm.footer__text
              )}
            >
              <li>
                Use at least 8 characters, a combination of numbers and letters
                is best.
              </li>
              <li>
                Do not use the same password you have used with us previously.
              </li>
              <li>
                Do not use dictionary words, your name, e-mail address, mobile
                phone number or other personal information that can be easily
                obtained.
              </li>
              <li>
                Do not use the same password for multiple online accounts.
              </li>
            </ul>
          </div>
        );
    }
  }

  return (
    <div className={"account-auth-form-container"}>
      <div
        className={cn(
          "account-auth-form account_auth-form",
          StylesLoginForm.authForm
        )}
      >
        {fromTemplate()}
      </div>

      {footerTemplate()}
    </div>
  );
};

export default PasswordAssistance;
