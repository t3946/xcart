import React from "react";
import { useBreakPoint } from "../../hooks/useBreakPoint";

export const SideBarMenu = () => {
  const breakpoints = useBreakPoint();

  return (
    <div>
      {breakpoints.is1920 && <div>Десктоп</div>}
      {breakpoints.is1366 && <div>Планшет</div>}
      {breakpoints.is768 && <div>Телефон</div>}
    </div>
  );
};
