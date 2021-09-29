import React from "react";
import $ from "jquery";
import { Provider } from "react-redux";
import HatNavigation from "@client/jsx/modules/account/components/hat/HatNavigation";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import ShadowPanel from "../modules/account/components/shared/ShadowPanel";
import DepartmentsMenuMobile from "@client/modules/account/components/hat/DepartmentsMenuMobile";
import HatSearchLine from "@client/modules/account/components/hat/HatSearchLine";

$(() => {
  const target = document.getElementById("header-target");

  if (!target) {
    return;
  }

  // eslint-disable-next-line @typescript-eslint/ban-ts-comment
  // @ts-ignore
  React.render(
    <Provider store={accountStore as any}>
      <DepartmentsMenuMobile />
      <HatNavigation />
      <ShadowPanel />
      <HatSearchLine isStatic={true} />
    </Provider>,
    target
  );
});
