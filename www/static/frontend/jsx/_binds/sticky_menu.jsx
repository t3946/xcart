import isMedia from "../utils/isMedia";
import cssFileLoaded from "../utils/cssFileLoaded";
import throttle from "lodash/throttle";

(() => {
  // После загрузки css
  $(document).on("app.start", function () {
    var stickyContainer = $(".sticky-menu-container");

    // выход, если нет прилипающего меню
    if (stickyContainer.length <= 0) {
      return;
    }

    var lastKnownScrollPosition = window.scrollY;
    var topSticky = 0;
    var ticking = false;

    let sticky = stickyContainer;

    let processScroll = function () {
      if (!ticking) {
        window.requestAnimationFrame(function () {
          checkMenuPosition();
          ticking = false;
        });
        ticking = true;
      }
    };

    let heightOfStickyBlock = sticky.height();

    let initStickyMenu = function () {
      // Выход если разрешение для мобильного устройства
      if (isMedia("large")) {
        stickyContainer.css({
          height: "auto",
          top: "-107px",
        });
        window.removeEventListener("scroll", processScroll);
        return;
      }

      if (heightOfStickyBlock <= 0) {
        return;
      }

      stickyContainer.css({
        height: heightOfStickyBlock + "px",
        top: topSticky + "px",
      });
      console.log("addEvent");
      window.addEventListener("scroll", processScroll);
    };

    let initStickyMenuOnResize = throttle(initStickyMenu, 50);

    function checkMenuPosition() {
      let delta = lastKnownScrollPosition - window.scrollY;
      if (-heightOfStickyBlock >= topSticky + delta) {
        topSticky = -heightOfStickyBlock;
      } else if (0 <= topSticky + delta) {
        topSticky = 0;
      } else {
        topSticky += delta;
      }

      stickyContainer.css({
        display: "block",
        height: heightOfStickyBlock + "px",
        top: topSticky + "px",
      });

      lastKnownScrollPosition = window.scrollY;
    }

    cssFileLoaded("styles.css", initStickyMenu);
    $(window).resize(initStickyMenuOnResize);
  });
})();
