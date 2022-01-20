import React from "react";
import LoginFormInputLogin from "@modules/account/components/authorization/LoginFormInputLogin";
import LoginFormInputPassword from "@modules/account/components/authorization/LoginFormInputPassword";
import LoginFormInputOTP from "@modules/account/components/authorization/LoginFormInputOTP";
import { loginAction } from "@redux/actions/account-actions/AutorizationActions";
import { useDispatch } from "react-redux";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import cn from "classnames";

import Styles from "@modules/account/components/authorization/LoginForm.module.scss";

const LoginForm: React.FC<any> = () => {
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);
  const dispatch = useDispatch();
  const INPUT_LOGIN_MODE = 0;
  const INPUT_PASSWORD_MODE = 1;
  const INPUT_OTP_MODE = 2;
  const [mode, setMode] = React.useState(INPUT_LOGIN_MODE);
  const [lastSentForm, setLastSentForm] = React.useState<any>({});
  const [password, setPassword] = React.useState<any>("");
  const [login, setLogin] = React.useState<any>("");
  const [rememberMe, setRememberMe] = React.useState<any>(false);

  function headerTemplate() {
    switch (mode) {
      case INPUT_LOGIN_MODE:
      case INPUT_PASSWORD_MODE:
        return "Sign-In";
      case INPUT_OTP_MODE:
        return "Two-Step Verification";
    }
  }

  function formTemplate() {
    switch (mode) {
      case INPUT_LOGIN_MODE:
        return (
          <LoginFormInputLogin
            lastSentForm={lastSentForm}
            setLastSentForm={setLastSentForm}
            goToPasswordInput={goToPasswordInput}
            submit={submit}
            setLogin={setLogin}
          />
        );
      case INPUT_PASSWORD_MODE:
        return (
          <LoginFormInputPassword
            login={lastSentForm.login}
            goToInputLogin={goToInputLogin}
            goToOTPInput={goToOTPInput}
            setPassword={setPassword}
            setRememberMe={setRememberMe}
          />
        );
      case INPUT_OTP_MODE:
        return (
          <LoginFormInputOTP
            login={login}
            password={password}
            rememberMe={rememberMe}
          />
        );
    }
  }

  function goToInputLogin(): void {
    setMode(INPUT_LOGIN_MODE);
  }

  function goToPasswordInput(): void {
    setMode(INPUT_PASSWORD_MODE);
  }

  function goToOTPInput(): void {
    setMode(INPUT_OTP_MODE);
  }

  function submit({ form, actions, success, error, complete }: any): void {
    setLastSentForm(form);
    dispatch(
      loginAction({
        form,

        success(res: any) {
          res.user && dispatch(userSetAction(res.user));
          success(res);
        },

        error,

        complete() {
          actions.setSubmitting(false);
          complete && complete();
        },
      })
    );
  }

  if (user) {
    router.push("/dashboard");
    return null;
  }

  return (
    <div className={"account-auth-form-container"}>
      <div
        className={cn(
          "account-auth-form",
          "account_auth-form",
          Styles.authForm
        )}
      >
        <h1 className="account-form-header px-12 px-sm-0 mb-md-20 mb-lg-14 text-center text-sm-start">
          {headerTemplate()}
        </h1>
        {formTemplate()}
      </div>
    </div>
  );
};

export default LoginForm;
