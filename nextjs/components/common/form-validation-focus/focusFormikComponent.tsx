import { useEffect } from "react";
import { useFormikContext } from "formik";

const ErrorFocus = () => {
  const { errors, isSubmitting, isValidating } = useFormikContext();

  useEffect(() => {
    if (isSubmitting && !isValidating) {
      const keys = Object.keys(errors);

      if (keys.length > 0) {
        const selector = `[name=${keys[0]}]`;
        console.log("selectpr:", selector);
        const errorElement = document.querySelector(selector) as HTMLElement;
        console.log(errorElement);
        if (errorElement) {
          errorElement.focus();
        }
      }
    }
  }, [errors, isSubmitting, isValidating]);

  return null;
};

export default ErrorFocus;
