import React from "react";
import RecoveryPassword from "@admin/modules/auth/components/recovery-password";
import $ from "jquery";

$(function () {
  const target = $("#recovery-password-target")[0];

  if (!target) {
    return;
  }

  React.render(<RecoveryPassword />, target);
});
