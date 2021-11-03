import React from "react";
import $ from "jquery";
import { Provider } from "react-redux";
import HatNavigation from "@client/jsx/modules/account/components/hat/HatNavigation";
import Store from "@client/jsx/redux/stores/Store";
import ShadowPanel from "../modules/account/components/shared/ShadowPanel";
import DepartmentsMenuMobile from "@client/modules/account/components/hat/DepartmentsMenuMobile";
import HatSearchLine from "@client/modules/account/components/hat/HatSearchLine";
import PhotoSwipeContainer from "@client/jsx/modules/product/Components/PhotoSwipe/PhotoSwipeContainer";
import MenuMobile from "@client/modules/account/components/hat/MenuMobile";

$(() => {
  const target = document.getElementById("header-target");

  if (!target) {
    return;
  }

  // eslint-disable-next-line @typescript-eslint/ban-ts-comment
  // @ts-ignore
  React.render(
    <Provider store={Store as any}>
      <DepartmentsMenuMobile />
      <HatNavigation />
      <ShadowPanel />
      <PhotoSwipeContainer />
      <MenuMobile isStatic={true} />
    </Provider>,
    target
  );
});

$(() => {
  const target = document.getElementById("hat-search-line-target");

  if (!target) {
    return;
  }

  // eslint-disable-next-line @typescript-eslint/ban-ts-comment
  // @ts-ignore
  React.render(
    <Provider store={Store as any}>
      <HatSearchLine isStatic={true} />
    </Provider>,
    target
  );

  const component = target.firstChild;
  target.parentNode.insertBefore(component, target);
  target.remove();
});
