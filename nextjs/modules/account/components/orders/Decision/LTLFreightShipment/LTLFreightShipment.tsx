import React from "react";
import InnerPage from "@components/common/inner-page/InnerPage";
import FormInputPhone, {
  phoneExtYupValidation,
  phoneYupValidation,
} from "@modules/account/components/shared/FormInputPhone";
import RadioQuestion from "modules/account/components/orders/Decision/LTLFreightShipment/RadioQuestion";
import { Form, Formik, FormikHelpers } from "formik";
import { useDispatch, useSelector } from "react-redux";
import { useRouter } from "next/router";
import StoreInterface from "@modules/account/ts/types/store.type";
import { setAlertAction } from "@redux/actions/account-actions/ProfileActions";
import {
  setIsVisibleAction as showMobileAlertAction,
  setMobileAlertAction,
} from "@redux/actions/account-actions/MobileMenuActions";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import * as Yup from "yup";
import cn from "classnames";
import { solveDecisionAction } from "@redux/actions/account-actions/DecisionsActions";
import Button from "@modules/ui/forms/Button";
import RadioQuestionStyles from "@modules/account/components/orders/Decision/LTLFreightShipment/RadioQuestion.module.scss";
import Styles from "@modules/account/components/orders/Decision/LTLFreightShipment/LTLFreightShipment.module.scss";
import Label from "@modules/ui/forms/Label";

const LTLFreightShipment: React.FC<any> = (props) => {
  const { decision, onChange } = props;
  const dispatch = useDispatch();

  React.useEffect(() => {
    return () => {
      dispatch(setAlertAction(null));
    };
  }, []);
  const mockData = [
    {
      label: "Commercial or residential delivery?",
      slug: "deliveryType",
      radios: [
        { label: "Commercial", value: "commercial" },
        { label: "Residential", value: "residential" },
      ],
    },
    {
      label: "If commercial, do you require a lift gate?",
      slug: "requireLiftGate",
      dependency: {
        question: "deliveryType",
        value: "commercial",
      },
      radios: [
        { label: "Yes", value: "yes" },
        { label: "No", value: "no" },
      ],
    },
    {
      label: "Curbside or inside delivery?",
      slug: "deliveryOutfit",
      ext: "Carriers may charge extra for residential, lift gate and inside delivery",
      radios: [
        { label: "Curbside delivery", value: "curbside" },
        { label: "Inside delivery", value: "inside" },
      ],
    },
  ];
  const getInitialValues = () => {
    const values: Record<string, string> = {};

    for (const q of mockData) {
      values[q.slug] = decision.options[q.slug];
    }

    values.phoneCode = "";
    values.phone = "";
    values.phone_ext = "";

    return values;
  };

  const getValidationScheme = () => {
    const fields: Record<string, any> = {
      phone: phoneYupValidation,
      phone_ext: phoneExtYupValidation,
      phoneCode: Yup.string().required("Required field"),
    };

    for (const q of mockData) {
      if (q.dependency) {
        fields[q.slug] = Yup.string().when(q.dependency.question, {
          is: q.dependency.value,
          then: Yup.string().required("Required"),
        });
      } else {
        fields[q.slug] = Yup.string().required("Required");
      }
    }
    return Yup.object().shape(fields);
  };

  function submit(values: Record<any, any>, helpers: FormikHelpers<any>) {
    helpers.setSubmitting(true);

    const data = {
      ...values,
      decision_id: decision.decision_id,
    };

    dispatch(
      solveDecisionAction({
        data,
        success() {
          helpers.setSubmitting(true);
        },
      })
    );

    onChange(`Thank you for providing us with the additional LTL freight information!
              We'll get back to you with the updated shipping cost.`);
  }

  return (
    <InnerPage
      hatClasses={Styles.hat}
      headerClasses={Styles.header}
      header={
        <>
          Questions on LTL{" "}
          <span className={Styles.headerText_mobile_capitalized}>
            freight shipment
          </span>
        </>
      }
      bodyClasses={"px-0"}
    >
      <Formik
        initialValues={getInitialValues()}
        validationSchema={getValidationScheme()}
        onSubmit={submit}
      >
        {({
          values,
          isSubmitting,
          handleChange,
          setFieldValue,
          touched,
          errors,
        }) => {
          return (
            <Form>
              <p
                className={cn(
                  Styles.text,
                  Styles.columnPadding,
                  Styles.pageBody__text
                )}
              >
                Due to your order being shipped by LTL freight, we need to ask a
                few questions prior to releasing your order.
              </p>
              {mockData.map((questionData, index) => (
                <RadioQuestion
                  checkedValues={values}
                  key={index}
                  question={questionData}
                  onChange={handleChange}
                  error={
                    touched[questionData.label] && errors[questionData.label]
                  }
                  classes={{ container: Styles.pageBody__container }}
                  disabled={isSubmitting || decision.solved}
                />
              ))}

              <div
                className={cn(
                  "row",
                  "align-items-center",
                  Styles.columnPadding
                )}
              >
                <Label
                  className={cn("mb-10", RadioQuestionStyles.questionLabel)}
                >
                  Phone number for delivery notice
                </Label>
                <div className="col-md-8 col-lg-8 col-xl-7 col-xxl-6">
                  <FormInputPhone
                    setFieldValue={setFieldValue}
                    handleChange={handleChange}
                    touched={touched}
                    errors={errors}
                    name={"phone"}
                    values={decision.solved ? decision.options : values}
                    mode={"ext"}
                    disabled={isSubmitting || decision.solved}
                  />
                </div>
              </div>

              <div className={Styles.pageBodySubmitButtonContainer}>
                <Button
                  type="submit"
                  className={cn(
                    "w-md-auto",
                    Styles.button,
                    Styles.pageBody__submitButton
                  )}
                  disabled={isSubmitting || decision.solved}
                >
                  Submit
                </Button>
              </div>
            </Form>
          );
        }}
      </Formik>
    </InnerPage>
  );
};

export default LTLFreightShipment;
