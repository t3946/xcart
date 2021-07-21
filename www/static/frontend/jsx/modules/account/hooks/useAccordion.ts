import { useRef, useState } from "react";

export function useAccordion() {
  const [height, setHeight] = useState(0);

  const [open, setOpen] = useState(false);

  const ref = useRef<HTMLDivElement>();

  const onItemClick = () => {
    if (!open) {
      setHeight(ref.current.scrollHeight);
    } else {
      setHeight(0);
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
