import React from "react";
import Login from "@admin/modules/auth/components/login";
import $ from "jquery";

$(function () {
  const target = $("#login-form-target")[0];

  if (!target) {
    return;
  }

  React.render(<Login />, target);
});
