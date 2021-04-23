import React from "react";
import { Accordion } from "@/modules/shared/components";
import { SwitchFormForType } from "../switch-form-for-type/SwitchFormForType";
import { HelpSectionItemDto } from "../../ts/types";

const HelpCenterSection: React.FC<HelpSectionItemDto> = ({ title, items }) => {
  return (
    <div className="navbar-wrap-items">
      <h3 className="navbar-wrap-title">{title}</h3>
      {items.map((section, id) => {
        const lastChild = items.length - 1 === id;
        return (
          <div key={id}>
            <Accordion lastChild={lastChild} text={section.question}>
              <span>{section.answer}</span>
              <SwitchFormForType type={section?.form_type} />
            </Accordion>
          </div>
        );
      })}
    </div>
  );
};

export default HelpCenterSection;
