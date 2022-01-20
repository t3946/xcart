import React from "react";
import InnerPage from "@modules/account/components/shared/InnerPage";
import FormInputPhone from "@modules/account/components/shared/FormInputPhone";
import RadioQuestion from "modules/account/components/orders/Decision/LTLFreightShipment/RadioQuestion";
import { getCountryByCode } from "@utils/Countries";
import { Formik, Form } from "formik";
import { useDispatch, useSelector } from "react-redux";
import { useRouter } from "next/router";
import Alert from "@modules/account/components/shared/Alert";
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
import { formAnswersLTLFreightShipmentAction } from "@redux/actions/account-actions/DecisionsActions";

import RadioQuestionStyles from "@modules/account/components/orders/Decision/LTLFreightShipment/RadioQuestion.module.scss";
import Styles from "@modules/account/components/orders/Decision/LTLFreightShipment/LTLFreightShipment.module.scss";
import Label from "@modules/ui/forms/Label";

const LTLFreightShipment: React.FC = () => {
  const countries = useSelector((e: StoreInterface) => e.countries);
  const dispatch = useDispatch();
  const breakpoint = useBreakpoint();
  const router = useRouter();

  const alert = useSelector((e: StoreInterface) => e.publicProfile.alert);
  const [show, setShow] = React.useState(alert !== null);
  if (alert) {
    breakpoint({
      xs: function () {
        dispatch(setAlertAction(null));
        dispatch(setMobileAlertAction(alert));
        dispatch(showMobileAlertAction(true));
        dispatch(setVisibleShadowPanelAction(true));
        router.push("/orders/decisions-required");
      },
      md: function () {},
    });
  }
  React.useEffect(() => {
    return () => {
      dispatch(setAlertAction(null));
    };
  }, []);
  const mockData = [
    {
      label: "Commercial or residential delivery?",
      radios: [
        { label: "Commercical", value: "commercial" },
        { label: "Residential", value: "residential" },
      ],
    },
    {
      label: "If commercial, do you require a lift gate?",
      dependency: {
        question: "Commercial or residential delivery?",
        value: "commercial",
      },
      radios: [
        { label: "Yes", value: "yes" },
        { label: "No", value: "no" },
      ],
    },
    {
      label: "Curbside or inside delivery?",
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
      values[q.label] = "";
    }

    values.phoneCountryCode = "";
    values.phone = "";
    values.phoneExtFieldName = "";

    return values;
  };

  const getValidationScheme = () => {
    const fields: Record<string, any> = {
      phone: Yup.string()
        .required("Required field")
        .max(50, "The maximum number of characters is 50")
        .matches(/[(]\d{3}[)] \d{3}[-]\d{4}/, "Is not in correct format"),
      phoneExt: Yup.string(),
      phoneCountryCode: Yup.string().required("Required field"),
    };

    for (const q of mockData) {
      if (q.dependency) {
        fields[q.label] = Yup.string().when(q.dependency.question, {
          is: q.dependency.value,
          then: Yup.string().required("Required"),
        });
      } else {
        fields[q.label] = Yup.string().required("Required");
      }
    }
    return Yup.object().shape(fields);
  };

  const submit = (values, actions) => {
    actions.setSubmittimg(true);

    const phoneCode = getCountryByCode(
      values.phoneCountryCode,
      countries
    ).phone_code;
    const form = {
      ...values,
      phone: `+${phoneCode}${values.phone}`.replace(/[()\-\s]/gim, ""),
    };
    dispatch(
      formAnswersLTLFreightShipmentAction({
        data: form,
        success() {
          setShow(true);
          dispatch(
            setAlertAction({
              variant: "decisionSuccess",
              message: `Thank you for providing us with the additional LTL freight information!
              We'll get back to you with the updated shipping cost.`,
            })
          );
          setTimeout(() => {
            setShow(false);
            dispatch(setAlertAction(null));
          }, 3000);
        },
      })
    );

    setShow(true);
    dispatch(
      setAlertAction({
        variant: "decisionSuccess",
        message: `Thank you for providing us with the additional LTL freight information!
              We'll get back to you with the updated shipping cost.`,
      })
    );
    setTimeout(() => {
      setShow(false);
      dispatch(setAlertAction(null));
    }, 3000);
  };
  return alert ? (
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
      <Alert
        show={show}
        variant={alert.variant}
        message={alert.message}
        classes={{
          container: "pt-20 pb-5 pt-lg-0",
          alert: ["account-inner-page_alert"],
        }}
      />
    </InnerPage>
  ) : (
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
                  disabled={isSubmitting}
                  onChange={handleChange}
                  error={
                    touched[questionData.label] && errors[questionData.label]
                  }
                  classes={{ container: Styles.pageBody__container }}
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
                    values={{
                      phoneCountryCode: values.phoneCountryCode,
                      phone: values.phone,
                    }}
                    mode={"ext"}
                  />
                </div>
              </div>

              <div className={Styles.pageBodySubmitButtonContainer}>
                <button
                  type="submit"
                  className={cn(
                    "form-button",
                    "w-md-auto",
                    Styles.button,
                    Styles.pageBody__submitButton
                  )}
                >
                  Submit
                </button>
              </div>
            </Form>
          );
        }}
      </Formik>
    </InnerPage>
  );
};

export default LTLFreightShipment;
