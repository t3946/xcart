import React from "react";
import CorporationEditSections from "@admin/modules/distributor/components/corporation-edit-sections";
import $ from "jquery";

$(function () {
  try {
    if (!appData.adminModule.corporationSections) {
      return;
    }
  } catch (e) {}

  const target = $("#corporations-sections-target")[0];

  if (!target) {
    return;
  }

  console.log("render");

  React.render(<CorporationEditSections />, target);
});
