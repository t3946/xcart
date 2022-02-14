import React from "react";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

export const useStickyHeader = () => {
  const breakpoint = useSelectorAccount((e) => e.main.breakpoint);
  let topSticky = 0;
  const [style, setStyle] = React.useState({});
  const ref = React.useRef<HTMLDivElement>();
  let lastScroll = 0;

  const updateStyle = () => {
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
  };
  React.useEffect(() => {
    breakpoint?.lg
      ? window.removeEventListener("scroll", updateStyle)
      : window.addEventListener("scroll", updateStyle);
    return () => window.removeEventListener("scroll", updateStyle);
  }, [breakpoint.lg]);

  return [style, ref];
};
