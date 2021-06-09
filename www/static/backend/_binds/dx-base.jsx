import React from "react";
import DistributorReference from "@admin/modules/distributor/components/distributor-reference";
import $ from "jquery";

$(function () {
  const target = $("#distributor-reference-target")[0];

  React.render(
    <DistributorReference {...appData.distributor.reference} />,
    target
  );
});
