import React from "react";
import InnerPage from "@modules/account/components/shared/InnerPage";
import UploadFile from "@modules/ui/UploadFile";
import Checkbox from "@modules/ui/forms/Checkbox";
import FormInputPhone from "@modules/account/components/shared/FormInputPhone";
import { Form, Formik } from "formik";
import * as yup from "yup";
import validatorMaxFileSize from "@utils/yup/validatorMaxFileSize";
import validatorFileFormat from "@utils/yup/validatorFileFormat";
import cn from "classnames";
import Feedback from "@modules/ui/forms/Feedback";
import StoreInterface from "@modules/account/ts/types/store.type";
import { useDispatch, useSelector } from "react-redux";
import { useRouter } from "next/router";
import Alert from "@modules/account/components/shared/Alert";
import { poAdditionalInformationRequiredAction } from "@redux/actions/account-actions/DecisionsActions";
import { setAlertAction } from "@redux/actions/account-actions/ProfileActions";
import {
  setIsVisibleAction as showMobileAlertAction,
  setMobileAlertAction,
} from "@redux/actions/account-actions/MobileMenuActions";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { getCountryByCode } from "@utils/Countries";

import Styles from "@modules/account/components/orders/Decision/POAdditionalInformationRequired/POAdditionalInformationRequired.module.scss";
import Label from "@modules/ui/forms/Label";

const initialValues = {
  file: [],
  phoneCountryCode: "",
  phone: "",
  phoneExt: "",
  isApprove: false,
};

const accessFileFormats = [
  "image/jpg",
  "image/jpeg",
  "image/png",
  "application/pdf",
];

const maxFileSizeMB = 10;

const getValidationScheme = (
  maxSize: number,
  accessFormats: string[],
  inputFileRef: React.MutableRefObject<Record<any, any>>
) => {
  return yup.object().shape({
    file: yup
      .mixed()
      .test(
        "fileSize",
        `Maximum uploaded file size: ${maxSize} MB`,
        validatorMaxFileSize(inputFileRef, maxSize)
      )
      .test(
        "fileType",
        "Unsupported File Format",
        validatorFileFormat(inputFileRef, accessFormats)
      ),
    isApprove: yup.bool().oneOf([true], "Need to approve"),
    phone: yup
      .string()
      .required("Required field")
      .max(50, "The maximum number of characters is 50")
      .matches(/[(]\d{3}[)] \d{3}[-]\d{4}/, "Is not in correct format"),
    phoneExt: yup.string(),
    phoneCountryCode: yup.string().required("Required field"),
  });
};

const columnPadding = cn("px-2", "px-md-3", "px-lg-4");

const classes = {
  columnPadding,
  text: cn(Styles.text),
  title: cn(Styles.title),
};

const POAdditionalInformationRequired: React.FC = () => {
  const countries = useSelector((e: StoreInterface) => e.countries);
  const dispatch = useDispatch();
  const [files, setFiles] = React.useState<File[]>([]);
  const inputFileRef = React.useRef<HTMLInputElement>();
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

  const submit = (values, actions) => {
    actions.setSubmitting(true);
    if (!files.length) {
      actions.setFieldError("file", "Need to upload PO order");
      actions.setSubmitting(false);
      return;
    }

    const phoneCode = getCountryByCode(
      values.phoneCountryCode,
      countries
    ).phone_code;

    const formData = new FormData();

    formData.append("file", inputFileRef.current.files[0]);
    formData.append(
      "phone",
      `+${phoneCode}${values.phone}`.replace(/[()\-\s]/gim, "")
    );
    formData.append("phoneExt", values.phoneExt);

    dispatch(
      poAdditionalInformationRequiredAction({
        data: formData,
        success(res) {
          setShow(true);
          dispatch(
            setAlertAction({
              variant: "decisionSuccess",
              message: `Thank you for providing us with missing information!
              We'll process your order ASAP.`,
            })
          );
          setTimeout(() => {
            setShow(false);
            dispatch(setAlertAction(null));
          }, 3000);
        },
      })
    );
    setTimeout(() => {
      // actions.setSubmitting(false);
      // setShow(true);
      // dispatch(
      //   setAlertAction({
      //     variant: "decisionSuccess",
      //     message: `Thank you for providing us with missing information!
      //         We'll process your order ASAP.`,
      //   })
      // );
    }, 3000);

    //
  };

  return alert ? (
    <InnerPage
      hatClasses={Styles.hat}
      header="PO: Additional information required"
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
    <Formik
      initialValues={initialValues}
      validationSchema={getValidationScheme(
        maxFileSizeMB,
        accessFileFormats,
        inputFileRef
      )}
      onSubmit={submit}
    >
      {({
        values,
        errors,
        handleChange,
        isSubmitting,
        touched,
        setFieldValue,
      }) => {
        return (
          <Form>
            <InnerPage
              hatClasses={Styles.hat}
              header="PO: Additional information required"
              bodyClasses={columnPadding}
              footerClasses={Styles.footer}
              footer={
                <button
                  type="submit"
                  disabled={isSubmitting}
                  className={cn(
                    "form-button",
                    "w-md-auto",
                    "mx-auto",
                    "mx-lg-0",
                    Styles.button
                  )}
                >
                  Submit
                </button>
              }
            >
              <p className={cn(classes.text, "mb-20", "mb-lg-4")}>
                Thank you very much for sending us your purchase order!
              </p>

              <p className={cn(classes.text, "mb-lg-20")}>
                Could you please provide us with the following information we
                are missing:
              </p>

              <div className={cn(classes.title, "mb-10", "mb-lg-20")}>
                Purchase order information
              </div>

              <div className="mb-12 mb-lg-14">Upload original PO</div>
              <UploadFile
                ref={inputFileRef}
                onChange={handleChange}
                files={files}
                setFiles={setFiles}
                name="file"
                disabled={isSubmitting}
                touched={!!touched.file}
                formats={accessFileFormats}
                maxSize={maxFileSizeMB}
                error={errors?.file}
                classNames={"mb-18 mb-lg-3"}
              />

              <Checkbox
                name="isApprove"
                checked={values.isApprove}
                onChange={handleChange}
                disabled={isSubmitting}
                classes={{ container: Styles.checkbox }}
                isValid={!!touched.isApprove && !errors.isApprove}
                isInvalid={!!touched.isApprove && !!errors.isApprove}
                label={
                  <>
                    I approve{" "}
                    <span
                      className={cn(Styles.green, {
                        [Styles.green_submitting]: isSubmitting,
                      })}
                    >
                      $85.52 total shipping charge
                    </span>{" "}
                    calculated by S3 Stores, Inc.
                  </>
                }
              />

              <Feedback className={cn("mb-3", "mb-lg-4")} type="invalid">
                {!!touched.isApprove && errors.isApprove}
              </Feedback>

              <div className={cn(classes.title, "mb-10", "mb-lg-20")}>
                Accounts payable
              </div>
              <div className="row align-items-center">
                <Label
                  className={cn(
                    "col-md-2",
                    "col-lg-1",
                    "mb-10",
                    "mb-md-0",
                    Styles.phoneLabel
                  )}
                >
                  Phone <span className="d-md-none">Number</span>
                </Label>
                <div className=" col-md-8 col-lg-8">
                  <FormInputPhone
                    setFieldValue={setFieldValue}
                    handleChange={handleChange}
                    touched={touched}
                    errors={errors}
                    name={"phone"}
                    disabled={isSubmitting}
                    values={{
                      phoneCountryCode: values.phoneCountryCode,
                      phone: values.phone,
                    }}
                    mode={"ext"}
                  />
                </div>
              </div>
            </InnerPage>
          </Form>
        );
      }}
    </Formik>
  );
};

export default POAdditionalInformationRequired;
