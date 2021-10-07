import React from "react";
import ReactDOM from "react-dom";
import { Provider } from "react-redux";
import { GeneralSettings } from "@admin/modules/general-settings/components/general-settings/general-settings";
import { generalSettingsStore } from "@redux/stores/generalSettingsStore";

(() => {
  const elem: HTMLElement = document.querySelector(".general-settings");

  if (!elem) return;

  ReactDOM.render(
    <Provider store={generalSettingsStore as any}>
      <GeneralSettings />
    </Provider>,
    elem
  );
})();
