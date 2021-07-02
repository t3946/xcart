import { useState } from "react";

export function useBreakPoint() {
  const [breakpoints, setBreakpoints] = useState(
    changeBreakPoints(window.innerWidth)
  );

  window.onresize = function (event) {
    setBreakpoints(changeBreakPoints(window.innerWidth));
  };

  return breakpoints;
}

function changeBreakPoints(resolution: number) {
  console.log(resolution);
  if (resolution > 1366) {
    return {
      is1920: true,
      is1366: false,
      is768: false,
    };
  }
  if (resolution > 768) {
    return {
      is1920: false,
      is1366: true,
      is768: false,
    };
  }
  return {
    is1920: false,
    is1366: false,
    is768: true,
  };
}
