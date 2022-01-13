import * as React from "react";
import { Form, Formik, FormikHelpers } from "formik";
import * as Yup from "yup";
import Feedback from "@modules/ui/forms/Feedback";
import Textarea from "@modules/ui/forms/Textarea";
import cn from "classnames";
import Styles from "@modules/account/components/leave-feedback/LeaveFeedback.module.scss";
import StylesInnerPage from "@modules/account/components/shared/InnerPage.module.scss";
import { sendFeedback } from "@redux/actions/account-actions/FeedbackActions";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";

const LeaveFeedback: React.FC<any> = function () {
  const dispatch = useDispatch();
  const router = useRouter();

  const initialFormValue = {
    message: "",
  };

  const categoryFormValidationSchema = Yup.object().shape({
    message: Yup.string().required("Message is required"),
  });

  function submit(values: Record<any, any>, helpers: FormikHelpers<any>) {
    helpers.setSubmitting(true);

    dispatch(
      sendFeedback({
        data: values,

        success() {
          helpers.setSubmitting(false);
          router.push("/dashboard");
        },
      })
    );
  }

  return (
    <div>
      <h1
        className={cn(StylesInnerPage.pageHeader, Styles.LeaveFeedback__header)}
      >
        Leave feedback
      </h1>

      <p
        className={cn(
          "px-10",
          "px-md-0",
          Styles.LeaveFeedback__howCanWeDo,
          Styles.howCanWeDo
        )}
      >
        How can we do better? Leave suggestions on improving account experience.
      </p>

      <Formik
        initialValues={initialFormValue}
        onSubmit={submit}
        validationSchema={categoryFormValidationSchema}
      >
        {({ errors, values, touched, handleChange, isSubmitting }) => {
          return (
            <Form encType="multipart/form-data">
              <div
                className={cn(
                  Styles.LeaveFeedback__textarea,
                  "px-10",
                  "px-md-0"
                )}
              >
                <Textarea
                  name={"message"}
                  onChange={handleChange}
                  value={values.message}
                  disabled={isSubmitting}
                  isInvalid={!!touched.message && !!errors.message}
                  isValid={touched.message && !errors.message}
                  className={Styles.textarea}
                />

                <Feedback type="invalid">
                  {touched.message && errors.message}
                </Feedback>
              </div>

              <div
                className={
                  "d-flex justify-content-center justify-content-lg-start"
                }
              >
                <button
                  className={cn("form-button", Styles.button)}
                  type="submit"
                  disabled={isSubmitting}
                >
                  send
                </button>
              </div>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};

export default LeaveFeedback;
