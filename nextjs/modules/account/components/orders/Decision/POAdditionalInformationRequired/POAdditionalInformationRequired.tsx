import React from "react";
import InnerPage from "@components/common/inner-page/InnerPage";
import UploadFile from "@modules/ui/UploadFile";
import Checkbox from "@modules/ui/forms/Checkbox";
import FormInputPhone, {
  phoneExtYupValidation,
  phoneYupValidation,
} from "@modules/account/components/shared/FormInputPhone";
import { Form, Formik } from "formik";
import * as yup from "yup";
import validatorMaxFileSize from "@utils/yup/validatorMaxFileSize";
import validatorFileFormat from "@utils/yup/validatorFileFormat";
import cn from "classnames";
import Feedback from "@modules/ui/forms/Feedback";
import { useDispatch } from "react-redux";
import { iSentOriginalPurchaseOrderViaFaxAction } from "@redux/actions/account-actions/DecisionsActions";
import Styles from "@modules/account/components/orders/Decision/POAdditionalInformationRequired/POAdditionalInformationRequired.module.scss";
import Label from "@modules/ui/forms/Label";
import getStoreUrl from "@utils/getStoreUrl";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

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
    phone: phoneYupValidation,
    phone_ext: phoneExtYupValidation,
    phoneCode: yup.string().required("Required field"),
  });
};

const columnPadding = cn("px-2", "px-md-3", "px-lg-4");

const classes = {
  columnPadding,
  text: cn(Styles.text),
  title: cn(Styles.title),
};

const POAdditionalInformationRequired: React.FC = (props) => {
  const { decision, onChange } = props;
  const dispatch = useDispatch();
  const [files, setFiles] = React.useState<File[]>([]);
  const inputFileRef = React.useRef<HTMLInputElement>();
  const initialValues = {
    file: [],
    phoneCode: (decision.solved && decision.options.phoneCode) || "",
    phone: (decision.solved && decision.options.phone) || "",
    phone_ext: (decision.solved && decision.options.phone_ext) || "",
    isApprove: false,
  };
  const companyName = useSelectorAccount((e) => e.config.site.corporationName);

  function submit(values, actions) {
    actions.setSubmitting(true);

    if (!files.length) {
      actions.setFieldError("file", "Need to upload PO order");
      actions.setSubmitting(false);
      return;
    }

    const formData = new FormData();

    formData.append("files[0]", inputFileRef.current.files[0]);
    formData.append("phone", values.phone.replace(/[()\-\s]/gim, ""));
    formData.append("phone_ext", values.phone_ext);
    formData.append("phoneCode", values.phoneCode);
    formData.append("decision_id", decision.decision_id);

    dispatch(
      iSentOriginalPurchaseOrderViaFaxAction({
        data: formData,
        success() {
          actions.setSubmitting(false);
        },
      })
    );

    onChange(`Thank you for providing us with missing information!
              We'll process your order ASAP.`);
  }

  function filesTemplate({ touched, errors, handleChange, isSubmitting }) {
    const templates = [
      <UploadFile
        ref={inputFileRef}
        onChange={handleChange}
        files={files}
        setFiles={setFiles}
        name="file"
        disabled={isSubmitting || decision.solved}
        touched={!!touched.file}
        formats={accessFileFormats}
        maxSize={maxFileSizeMB}
        error={errors?.file}
        classNames={"mb-18 mb-lg-3"}
      />,
    ];

    if (decision.solved) {
      const fileTemplates = [];
      let i = 0;

      for (const file of decision.files) {
        const { path, original_name } = file.file;

        fileTemplates.push(
          <li key={`file-${i}`}>
            <a href={getStoreUrl(path)} target="_blank">
              {original_name}
            </a>
          </li>
        );

        i++;
      }

      templates.push(<ul>{fileTemplates}</ul>);
    }

    return templates;
  }

  return (
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
                  disabled={isSubmitting || decision.solved}
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

              {filesTemplate({ touched, errors, handleChange, isSubmitting })}

              <Checkbox
                name="isApprove"
                checked={values.isApprove || !!decision.solved}
                onChange={handleChange}
                disabled={isSubmitting || decision.solved}
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
                      $
                      {parseFloat(decision.options.totalShippingCharge).toFixed(
                        2
                      )}{" "}
                      total shipping charge
                    </span>{" "}
                    calculated by {companyName}
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
                    disabled={isSubmitting || decision.solved}
                    values={values}
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
