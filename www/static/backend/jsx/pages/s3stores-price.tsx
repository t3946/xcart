import ReactDOM from "react-dom";
import React from "react";
import {FormPrice} from "@admin/modules/distributor/components/form-price/form-price";

(() => {
    const elem: HTMLElement = document.querySelector(".dx-price");

    if (!elem) return;

    ReactDOM.render(
        <FormPrice distributorId={elem?.dataset?.id}/>,
        elem
    );
})();
