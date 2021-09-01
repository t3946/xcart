import React from "react";
import LoginForm from "@/modules/account/components/authorization/LoginForm";
import $ from "jquery";

$(() => {
  const target = document.getElementById("account-login-form-target");

  if (!target) {
    return;
  }

  React.render(<LoginForm />, target);
});
