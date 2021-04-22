import * as React from "react";
import YourOrderForm from "./YourOrderForm";
import { Accordion } from "@/modules/shared/components";

const YourOrder: React.FC = () => {
  return (
    <div>
      <h3>Product question</h3>
      <Accordion lastChild={true} text="Will you be getting more stock?">
        <YourOrderForm></YourOrderForm>
      </Accordion>
    </div>
  );
};

export default YourOrder;
