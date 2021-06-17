import React from "react";
import HatReference from "@admin/modules/hat/hat-reference";
import $ from "jquery";

$(function () {
  if (!appData.hat) {
    return;
  }

  const target = $("#admin-hat-target")[0];

  if (!target) {
    return;
  }

  React.render(<HatReference />, target);
});
