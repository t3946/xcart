import React from "react";
import InnerPage from "@components/common/inner-page/InnerPage";
import UploadFile from "@modules/ui/UploadFile";
import cn from "classnames";
import { Formik, Form, FormikHelpers } from "formik";
import validatorMaxFileSize from "@utils/yup/validatorMaxFileSize";
import validatorFileFormat from "@utils/yup/validatorFileFormat";
import * as yup from "yup";
import { accessRecovery } from "@redux/actions/account-actions/TSVActions";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";
import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";
import Styles from "@modules/account/components/login-and-security/TSVRecovery.module.scss";
import useSnackbar from "@modules/account/hooks/useSnackbar";

const TSVRecovery: React.FC<any> = function () {
  const dispatch = useDispatch();
  const router = useRouter();
  const inputFileRef = React.useRef<HTMLInputElement>();
  const [files, setFiles] = React.useState<File[]>([]);

  const accessFileFormats = [
    "image/jpg",
    "image/jpeg",
    "image/png",
    "application/pdf",
    "application/msword",
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
  ];

  const maxFileSizeMB = 10;

  const initialValues = {
    file: [],
  };
  const snackbar = useSnackbar();

  const validationSchema = yup.object().shape({
    file: yup
      .mixed()
      .test(
        "fileSize",
        `Maximum uploaded file size: ${maxFileSizeMB} MB`,
        validatorMaxFileSize(inputFileRef, maxFileSizeMB)
      )
      .test(
        "fileType",
        "Unsupported File Format",
        validatorFileFormat(inputFileRef, accessFileFormats)
      ),
  });

  function submit(values: any, actions: FormikHelpers<any>) {
    if (!files.length) {
      actions.setFieldError("file", "Need to upload a document");
      return;
    }

    actions.setSubmitting(true);

    const formData = new FormData();
    formData.append("file", inputFileRef.current.files[0]);
    formData.append("login", router.query.login);

    dispatch(
      accessRecovery({
        data: formData,
        success(res) {
          snackbar.show("Your recovery request was sent");
        },
        finally() {
          actions.setSubmitting(false);
        },
      })
    );
  }

  return (
    <InnerPage
      header={"Two-Step Verification Account Recovery"}
      bodyClasses={cn("p-0", StylesLoginAndSecurity.pageBody)}
    >
      <Formik
        initialValues={initialValues}
        onSubmit={submit}
        validationSchema={validationSchema}
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
              <div className={"content-panel"}>
                <p>
                  To regain access to your account, you'll need to verify your
                  identity. To do so, you'll need to provide a picture (a scan,
                  or a photo) of a government-issued identity document.
                  Acceptable forms of government-issued identification include:
                </p>

                <ul>
                  <li>A state-issued driver license</li>
                  <li>A state ID card</li>
                  <li>A voter registration card</li>
                </ul>

                <strong>Before uploading, please make sure that:</strong>

                <ul>
                  <li>
                    Any sensitive information, such as account number or
                    identification numbers, are covered, concealed, or removed.
                  </li>
                  <li>
                    Your name and address, as well as the issuing authority
                    (e.g., state or country) are clearly visible.
                  </li>
                </ul>

                <p>
                  The verification process may take 1-2 days to complete. We
                  will send an email to <b>albert.einstein@gmail.com</b> once
                  Two Step Verification has been disabled. You will then be able
                  to access your account, with only your password. You can
                  re-enable Two-Step Verification at any time.
                </p>

                <h2>
                  Upload a document
                  <div className={Styles.fileFormate}>
                    (Acceptable document types: DOC, DOCX, JPG, JPEG, PDF,
                    PJPEG, PNG)
                  </div>
                </h2>

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
                  classNames={"mb-0"}
                />
              </div>

              <div
                className={"text-md-center text-lg-start account-page-footer"}
              >
                <button
                  className={
                    "form-button d-md-inline-block tsv-recovery-submit-button"
                  }
                  disabled={isSubmitting}
                  type={"submit"}
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

export default TSVRecovery;
