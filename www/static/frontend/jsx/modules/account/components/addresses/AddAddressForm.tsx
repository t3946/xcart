import React from "react";
import { FormInput } from "../shared/FormInput";
import { FormSelect } from "../shared/FormSelect";
import { Button } from "@material-ui/core";
import { FormCheckBox } from "../shared/FormCheckBox";
import { Form, Formik } from "formik";
import {
  initialAddAddressFormValue,
  addAddressFormValidationSchema,
} from "../../ts/consts/add-address-form";

export const AddAddressForm = () => {
  const selectItems = [
    { value: 1, viewValue: "2" },
    { value: 1, viewValue: "2" },
    { value: 1, viewValue: "2" },
    { value: 1, viewValue: "2" },
    { value: 1, viewValue: "2" },
    { value: 1, viewValue: "2" },
  ];
  return (
    <div className="add-address-form-container">
      <Formik
        initialValues={initialAddAddressFormValue}
        onSubmit={null}
        validationSchema={addAddressFormValidationSchema}
      >
        {({ errors, setFieldValue, values, touched }) => {
          return (
            <Form className="your-order-form" encType="multipart/form-data">
              <FormSelect
                items={selectItems}
                value={values.country}
                label={"Country"}
              />
              <FormInput
                label={"Full Name (First and Last name)"}
                placeholder={"Albert H. Einstein"}
                value={values.name}
                name={"name"}
                errorMessage={errors.name}
              />
              <FormInput
                label={"Phone Number"}
                value={values.phone}
                name={"phone"}
                errorMessage={errors.phone}
              />
              <FormInput
                placeholder="Street address or P.O. Box"
                label={"Address"}
                value={values.address_street}
                name={"address_street"}
                errorMessage={errors.address_street}
              />
              <FormInput
                placeholder="Apt, suite, unit, building, floor, etc."
                value={values.address}
                name={"address"}
                errorMessage={errors.address}
              />
              <FormInput
                label={"City"}
                placeholder="Jackson"
                value={values.city}
                name={"city"}
                errorMessage={errors.city}
              />
              <FormSelect
                items={selectItems}
                value={values.state}
                label={"State/Province"}
              />
              <FormInput
                label={"Zip/Postal Code"}
                placeholder="39213"
                value={values.zip}
                errorMessage={errors.zip}
                name={"zip"}
              />
              <FormCheckBox
                label={"Make this my default address"}
                value={values.default}
                name={"default"}
              />
              <Button type={"submit"} className="account-submit-btn">
                Add
              </Button>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};
