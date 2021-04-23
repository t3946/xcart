import * as React from "react";
import { Form, Formik } from "formik";
import { HelpFormInput } from "@/modules/shared/components";
import {
  initialFormValue,
  categoryFormValidationSchema,
} from "../../ts/consts";
import HelpFormPhoneInput from "../help-form-phone-input/HelpFormPhoneInput";

const YourOrderForm: React.FC = () => {
  return (
    <div>
      <Formik
        initialValues={initialFormValue}
        onSubmit={null}
        validationSchema={categoryFormValidationSchema}
      >
        {({ errors, setFieldValue, values, touched }) => {
          return (
            <Form className="your-order-form" encType="multipart/form-data">
              <HelpFormInput
                value={values.name}
                clear={setFieldValue}
                required={true}
                error={Boolean(errors.name) && touched.name}
                valid={!Boolean(errors.name) && touched.name}
                errorMessage={errors.name}
                name="name"
                label="Название"
              />
              <HelpFormInput
                value={values.email}
                clear={setFieldValue}
                required={true}
                error={Boolean(errors.email) && touched.email}
                valid={!Boolean(errors.email) && touched.email}
                errorMessage={errors.email}
                name="email"
                label="Описание"
              />
              <HelpFormPhoneInput
                value={values.phone}
                clear={setFieldValue}
                required={true}
                errorExt={Boolean(errors.phone_ext) && touched.phone_ext}
                error={Boolean(errors.phone) && touched.phone}
                errorMessage={errors.phone || errors.phone_ext}
                name="phone"
                label="Цена"
                extName="phone_ext"
                valueExt={values.phone_ext}
                valid={!Boolean(errors.phone) && touched.phone}
                validExt={!Boolean(errors.phone_ext) && touched.phone_ext}
              />
              <HelpFormInput
                value={values.question}
                clear={setFieldValue}
                required={true}
                error={Boolean(errors.question) && touched.question}
                valid={!Boolean(errors.question) && touched.question}
                errorMessage={errors.question}
                name="question"
                label="Количество"
                as={"textarea"}
              />
              <div className="formik-input-wrap">
                <button className="formik-submit-button" type="submit">
                  SUBMIT QUESTION
                </button>
              </div>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};

export default YourOrderForm;
