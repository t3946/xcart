import storeApp from "../redux/stores/StoreApp";
import { hideAll } from "../redux/reduсers/appHeadReduсer";

(() => {
  $(".shadow").on("click touchstart", hideAll);

  let unsubscribe = storeApp.subscribe(() => {
    let state = storeApp.getState();

    if (state.frontend) {
      if (state.frontend.darkness) {
        $(".shadow").addClass("active");
      } else {
        $(".shadow").removeClass("active");
      }
    }
  });
})();
