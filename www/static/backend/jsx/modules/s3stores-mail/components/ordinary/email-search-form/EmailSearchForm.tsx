import React from "react";
import { Form, Formik } from "formik";
import FormInput from "../../../../shared/components/form-input/FormInput";
import { initialFormValues } from "@s3stores-mail/ts/consts";

export const EmailSearchForm: React.FC = () => {
  return (
    <Formik
      initialValues={initialFormValues}
      onSubmit={null}
      validationSchema={null}
    >
      {() => {
        return (
          <Form className="your-order-form" encType="multipart/form-data">
            <FormInput
              required={true}
              // error={Boolean(errors.name) && touched.name}
              // valid={!Boolean(errors.name) && touched.name}
              // errorMessage={errors.name}
              name="from"
              label="Название"
            />
            <FormInput
              required={true}
              // error={Boolean(errors.email) && touched.email}
              // valid={!Boolean(errors.email) && touched.email}
              // errorMessage={errors.email}
              name="to"
              label="Описание"
            />
            <FormInput required={true} name="subject" label="Цена" />
            <FormInput required={true} name="words" label="Количество" />
            <FormInput required={true} name="doesntHave" label="Количество" />
            <FormInput required={true} name="dateRange" label="Количество" />
            <div className="formik-input-wrap">
              <button className="formik-submit-button" type="submit">
                SEARCH
              </button>
            </div>
          </Form>
        );
      }}
    </Formik>
  );
};
