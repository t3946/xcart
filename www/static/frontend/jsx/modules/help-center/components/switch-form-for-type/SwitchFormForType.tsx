import React from "react";
import { FormType } from "../../ts/consts";
import YourOrderForm from "../your-order/YourOrderForm";

export const SwitchFormForType: React.FC<any> = ({ type = null }) => {
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
