import React from "react";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

export const useStickyHeader = () => {
  const breakpoint = useBreakpoint();
  const lg = useSelectorAccount((e) => e.main.breakpoint?.lg);
  let topSticky = 0;
  const [style, setStyle] = React.useState({});
  const ref = React.useRef<HTMLDivElement>();
  let lastScroll = 0;

  function removeStickyHandler() {
    setStyle({
      top: "-107px",
      position: "sticky",
    });
    document.removeEventListener("scroll", updateStyle);
  }

  function updateStyle(e) {
    window.requestAnimationFrame(function () {
      const delta = lastScroll - window.scrollY;
      if (-ref.current.offsetHeight >= topSticky + delta) {
        topSticky = -ref.current.offsetHeight - 1;
      } else if (0 <= topSticky + delta) {
        topSticky = 0;
      } else {
        topSticky += delta;
      }
      setStyle({
        top: topSticky + "px",
        position: "sticky",
      });
      lastScroll = window.scrollY;
    });
  }
  React.useEffect(() => {
    breakpoint({
      xs: function () {
        document.addEventListener("scroll", updateStyle);
      },
      lg: removeStickyHandler,
    });

    return removeStickyHandler;
  }, [lg]);

  React.useEffect(() => {
    return removeStickyHandler;
  }, []);

  return [style, ref];
};
