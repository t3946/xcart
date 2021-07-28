import { useEffect } from "react";

function useCLickListener(func: (arg: boolean) => void): void {
  const handleFunction = () => {
    func(false);
  };

  useEffect(() => {
    window.addEventListener("click", handleFunction);
    return () => {
      window.removeEventListener("click", handleFunction);
    };
  }, []);
}

export default useCLickListener;
