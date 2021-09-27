"use strict";

import "@/js/main";
import "preact/debug";
import "./_head.jsx";
import "./_binds/pages/account";
import "./_binds/header-target";
import "bootstrap/dist/js/bootstrap.bundle.js";

import foundationRegisterCustomEvents from "./_binds/foundation_events";

import createResizeMonitor from "./components/ResizeMonitor";
import DepartmentMenu from "./components/DepartmentMenu";
import CatalogFilter from "./components/CatalogFilter";
import Search from "./components/Search";
import isMedia from "./utils/isMedia";
import documentReady from "./utils/documentReady";
import Waves from "node-waves";

(function () {
  documentReady(() => {
    createResizeMonitor();
    new Search();
    new DepartmentMenu();
    new CatalogFilter();

    isMedia("medium", "(max-width: 1023px)");
    isMedia("large", "(min-width: 1024px)");

    Waves.attach(".waves");
    Waves.init();

    $(document).on("click", ".show_more", function (e) {
      let $this = $(this);
      let $target = $($this.data("target"));

      if (!$target.hasClass("full")) {
        $target.addClass("full");

        $this.html($this.data("text-less"));
      } else {
        $target.removeClass("full");

        $this.html($this.data("text-more"));
      }
    });

    $(document).on("click", "form button", function (event) {
      $(event.target).parents("form").addClass("tried_to_submit");
    });

    loader.detach(() => {
      $(".off-canvas").removeClass("hide");

      $(document).foundation();

      while (window.app.afterReady.length) {
        window.app.afterReady.pop()();
      }

      foundationRegisterCustomEvents();
    });

    window.surfMetaRegister();
    $(document).trigger("app.start");

    Promise.all([
      new FontFaceObserver("Lato", {
        style: "normal",
        weight: 400,
      }).load(),
      new FontFaceObserver("Lato", {
        style: "normal",
        weight: 700,
      }).load(),
    ]).then(
      function () {
        let event = new CustomEvent("font.loaded", { detail: true });
        document.font = true;
        document.dispatchEvent(event);
      },
      function () {
        let event = new CustomEvent("font.loaded", { detail: false });
        document.dispatchEvent(event);
      }
    );
  });
})();
