import React from "react";
import RegisterForm from "@/modules/account/components/authorization/RegisterForm";
import $ from "jquery";

$(() => {
  const target = document.getElementById("account-register-form-target");

  if (!target) {
    return;
  }

  React.render(<RegisterForm />, target);
});
