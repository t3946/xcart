import { Button } from "@material-ui/core";
import React from "react";
import { FormInput } from "../shared/FormInput";

export const AddAddressForm = () => {
  return (
    <div className="add-address-form-container">
      <FormInput label={"Hello"} />
      <FormInput label={"Hello"} />
      <FormInput label={"Hello"} />
      <FormInput label={"Hello"} />
      <FormInput label={"Hello"} />
      <Button className="account-submit-btn add-address-btn">Add</Button>
    </div>
  );
};
