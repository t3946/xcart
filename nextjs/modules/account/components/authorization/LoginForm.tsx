import React from "react";
import LoginFormInputLogin from "@modules/account/components/authorization/LoginFormInputLogin";
import LoginFormInputPassword from "@modules/account/components/authorization/LoginFormInputPassword";
import LoginFormInputOTP from "@modules/account/components/authorization/LoginFormInputOTP";
import classNames from "classnames";
import { loginAction } from "@redux/actions/account-actions/AutorizationActions";
import { useDispatch } from "react-redux";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";

const LoginForm: React.FC<any> = () => {
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);
  const dispatch = useDispatch();

  const INPUT_LOGIN_MODE = 0;
  const INPUT_PASSWORD_MODE = 1;
  const INPUT_OTP_MODE = 2;
  const [mode, setMode] = React.useState(INPUT_LOGIN_MODE);
  const [lastSentForm, setLastSentForm] = React.useState<any>({});

  React.useEffect(() => {
    if (user !== null) {
      router.push("/");
    }
  });

  if (user !== null) {
    return null;
  }

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
          />
        );
      case INPUT_PASSWORD_MODE:
        return (
          <LoginFormInputPassword
            login={lastSentForm.login}
            goToInputLogin={goToInputLogin}
            goToOTPInput={goToOTPInput}
          />
        );
      case INPUT_OTP_MODE:
        return (
          <LoginFormInputOTP lastSentForm={lastSentForm} submit={submit} />
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

  return (
    <div className={"account-auth-form-container"}>
      <div className="account-auth-form account_auth-form">
        <h1 className="account-form-header px-12 px-sm-0">
          {headerTemplate()}
        </h1>
        {formTemplate()}
      </div>
    </div>
  );
};

export default LoginForm;
