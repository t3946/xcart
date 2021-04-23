import React from "react";
import { FormType } from "../../ts/consts";
import { SwitchFormForTypeDto } from "../../ts/types";
import YourOrderForm from "../your-order-form/YourOrderForm";

export const SwitchFormForType: React.FC<SwitchFormForTypeDto> = ({ type }) => {
  function switchFormForType() {
    switch (type) {
      case FormType.QUESTION: {
        return <YourOrderForm />;
      }
      default: {
        return <></>;
      }
    }
  }
  return <>{switchFormForType()}</>;
};
