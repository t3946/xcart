import React from "react";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import InputPhone from "@components/pages/login-and-security/edit-phone/InputPhone";
import InputOTP from "@components/pages/login-and-security/edit-phone/InputOTP";
import InputPassword from "@components/pages/login-and-security/edit-phone/InputPassword";

const EditEmail = (): any => {
  const user = useSelectorAccount((e) => e.user);
  const [step, setStep] = React.useState("send-otp");
  const [secret, setSecret] = React.useState("");
  const [newPhone, setNewPhone] = React.useState("new-email");

  if (!user) {
    return null;
  }

  return (
    <div>
      {step === "send-otp" && (
        <InputPhone
          setStep={setStep}
          setSecret={setSecret}
          currentPhone={user.phone}
          setNewPhone={setNewPhone}
        />
      )}

      {step === "check-otp" && (
        <InputOTP setStep={setStep} secret={secret} newPhone={newPhone} />
      )}

      {step === "change-phone" && <InputPassword newPhone={newPhone} setStep={setStep} />}
    </div>
  );
};

export default EditEmail;
