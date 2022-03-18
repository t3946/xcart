import React from "react";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import InputEmail from "@components/pages/login-and-security/edit-email/InputEmail";
import InputOTP from "@components/pages/login-and-security/edit-email/InputOTP";
import InputPassword from "@components/pages/login-and-security/edit-email/InputPassword";

const EditEmail = (): any => {
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

export default EditEmail;
