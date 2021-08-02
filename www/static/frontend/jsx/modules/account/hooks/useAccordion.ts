import { useRef, useState } from "react";

export function useAccordion() {
  const [height, setHeight] = useState<string | number>(0);

  const [open, setOpen] = useState(false);

  const ref = useRef<HTMLDivElement>();

  const onItemClick = () => {
    if (!open) {
      setHeight(ref.current.scrollHeight);
      setTimeout(() => {
        setHeight("auto");
      }, 300);
    } else {
      setHeight(ref.current.clientHeight);
      setTimeout(() => {
        setHeight(0);
      }, 10);
    }
    setOpen(!open);
  };

  return {
    height,
    open,
    ref,
    onItemClick,
  };
}
