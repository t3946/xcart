import * as React from "react";
import Collapsible from "react-collapsible";
import { AccordionPropsDto } from "@/modules/shared/ts/types";

const Accordion: React.FC<AccordionPropsDto> = ({
  children,
  text,
  lastChild = false,
}) => {
  return (
    <>
      <hr className="Collapsible__hr" />
      <Collapsible trigger={text}>{children}</Collapsible>
      {lastChild ? <hr className="Collapsible__hr" /> : null}
    </>
  );
};

export default Accordion;
