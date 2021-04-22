import React from "react";
import { Accordion } from "@/modules/shared/components";
import { SwitchFormForType } from "../switch-form-for-type/SwitchFormForType";

const HelpCenterSection: React.FC<any> = ({ item }) => {
  return (
    <div className="navbar-wrap-items">
      <h3 className="navbar-wrap-title">{item.title}</h3>
      {item.items.itemContent.map((section, id) => {
        const lastChild = item.items.itemContent.length - 1 === id;
        return (
          <div key={id}>
            <Accordion lastChild={lastChild} text={section.answer}>
              <span>{section.question}</span>
              <SwitchFormForType type={section?.formType}></SwitchFormForType>
            </Accordion>
          </div>
        );
      })}
    </div>
  );
};

export default HelpCenterSection;
