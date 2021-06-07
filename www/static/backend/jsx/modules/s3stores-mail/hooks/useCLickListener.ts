import { useEffect } from "react";

export function useCLickListener(func: (arg: boolean) => void): void {
  useEffect(() => {
    window.addEventListener("click", () => {
      func(false);
    });
    return window.removeEventListener("click", () => {
      func(false);
    });
  }, []);
}
