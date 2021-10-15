import React from "react";
import { FormSelect } from "@client/modules/account/components/shared/FormSelect";
import { FormInput } from "@client/modules/account/components/shared/FormInput";
import { problemsWithOrderSelectValue } from "@client/modules/account/ts/consts/order-actions-select.const";

interface ProblemWithOrderProps {}

export const ProblemWithOrder: React.FC<ProblemWithOrderProps> = () => {
  return (
    <div className="order-product-list-body">
      <div className="page-label order-actions-page-label problem-with-order-label">
        Problem with order
      </div>
      <p>What went wrong?</p>
      <FormSelect
        classes={{ selectHeader: "order-product-select-errors" }}
        value={problemsWithOrderSelectValue[0]}
        items={problemsWithOrderSelectValue}
        id={"problem-with-order-select"}
      />
      <FormInput
        inputType="textarea"
        name={"aw"}
        handleChange={null}
        value={null}
        id={"132"}
        placeholder="Explain why you would like to return products for a refund
or replace them with the same or different products"
        classes={{
          input: "order-cancel-items-textarea-input",
          textArea: "order-cancel-items-textarea",
          group: "order-cancel-items-textarea-group",
        }}
      />
      <button className="form-button problem-with-order-send-btn">send</button>
    </div>
  );
};
