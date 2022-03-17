import React from "react";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import InputEmail from "@modules/account/components/login-and-security/change-email/InputEmail";
import InputOTP from "@modules/account/components/login-and-security/change-email/InputOTP";
import InputPassword from "@modules/account/components/login-and-security/change-email/InputPassword";

const FormEditUserEmail = (): any => {
  const user = useSelectorAccount((e) => e.user);
  const [step, setStep] = React.useState("send-otp");
  const [secret, setSecret] = React.useState("");
  const [newEmail, setNewEmail] = React.useState("new-email");

  if (!user) {
    return null;
  }

  return (
    <div>
      {step === "send-otp" && (
        <InputEmail
          setStep={setStep}
          setSecret={setSecret}
          currentEmail={user.email}
          setNewEmail={setNewEmail}
        />
      )}

      {step === "check-otp" && (
        <InputOTP setStep={setStep} secret={secret} newEmail={newEmail} />
      )}

      {step === "change-email" && <InputPassword newEmail={newEmail} />}
    </div>
  );
};

export default FormEditUserEmail;
