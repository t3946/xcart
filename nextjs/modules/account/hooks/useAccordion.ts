import { MutableRefObject, useRef, useState } from "react";

interface AccordionData {
  height: string | number;
  open: boolean;
  ref: MutableRefObject<HTMLDivElement>;
  onItemClick: () => void;
}

export function useAccordion(timeout = 300, initOpen = false): AccordionData {
  const [height, setHeight] = useState<string | number>(initOpen ? "auto" : 0);

  const [open, setOpen] = useState(initOpen);

  const ref = useRef<HTMLDivElement>();

  const onItemClick = () => {
    if (!open) {
      setHeight(ref.current.scrollHeight);
      setTimeout(() => {
        setHeight("auto");
      }, timeout);
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
