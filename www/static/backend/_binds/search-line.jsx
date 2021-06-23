import React from "react";
import SearchLine from "@admin/modules/hat/search-line";
import $ from "jquery";

$(function () {
  if (!appData.hat) {
    return;
  }

  const target = $("#admin-search-line-target")[0];

  if (!target) {
    return;
  }

  React.render(<SearchLine />, target);
});
