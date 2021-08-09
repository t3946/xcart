import React from 'react';
import ReactDOM from "react-dom";
import {GeneralSettings} from "@admin/modules/general-settings/components/general-settings/general-settings";

(() => {
    const elem: HTMLElement = document.querySelector(".general-settings");

    if (!elem) return;

    ReactDOM.render(
        <GeneralSettings/>,
        elem
    );
})();