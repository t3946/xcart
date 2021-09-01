import React from "react";
import SidebarMenu from "@admin/modules/sidebar/components/menu";
import $ from "jquery";

$(function () {
  if (!appData.sidebarMenu) {
    return;
  }

  const target = $("#sidebar-menu-target")[0];

  if (!target) {
    return;
  }

  React.render(<SidebarMenu />, target);
});
