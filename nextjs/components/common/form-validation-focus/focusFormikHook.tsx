import React, { useEffect } from "react";


/**
 * @param {number} formik Объект useFormik()
 */
const ErrorFocus = ({ formik }) => {
  const { errors, isSubmitting, isValidating } = formik;

  useEffect(() => {
    if (isSubmitting && !isValidating) {
      let keys = Object.keys(errors);
      if (keys.length > 0) {
        const selector = `[name=${keys[0]}]`;
        const errorElement = document.querySelector(selector) as HTMLElement;
        if (errorElement) {
          errorElement.focus();
        }
      }
    }
  }, [errors, isSubmitting, isValidating]);

  return null;
};

export default ErrorFocus;
