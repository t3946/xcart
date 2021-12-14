import React from "react";
import UploadFile from "@modules/ui/UploadFile";
import { Formik, Form } from "formik";
import * as yup from "yup";
import cn from "classnames";
import validatorFileFormat from "@utils/yup/validatorFileFormat";
import validatorMaxFileSize from "@utils/yup/validatorMaxFileSize";
import Styles from "@modules/account/components/orders/Decision/OriginalPurchaseOrder/OriginalPurchaseOrder.module.scss";
import { useDispatch } from "react-redux";
import { uploadOriginalPurchaseOrderAction } from "@redux/actions/account-actions/DecisionsActions";
import DecisionsInterface from "@modules/account/ts/types/decision";

interface IProps {
  onChange: (decision: DecisionsInterface) => any;
  decision: DecisionsInterface;
}

const AsFile: React.FC<IProps> = (props: IProps) => {
  const { onChange, decision } = props;
  const [files, setFiles] = React.useState<File[]>([]);
  const dispatch = useDispatch();
  const inputFileRef = React.useRef<HTMLInputElement>();
  const initialValues = {
    file: "",
  };
  const maxMB = 10;
  const SUPPORTED_FORMATS = [
    "image/jpg",
    "image/jpeg",
    "image/png",
    "application/pdf",
  ];
  const validationSchema = yup.object().shape({
    file: yup
      .mixed()
      .test(
        "fileSize",
        `Maximum uploaded file size: ${maxMB} MB`,
        validatorMaxFileSize(inputFileRef, maxMB)
      )
      .test(
        "fileType",
        "Unsupported File Format",
        validatorFileFormat(inputFileRef, SUPPORTED_FORMATS)
      ),
  });
  const submit = ({ setSubmitting }) => {
    const formData = new FormData();
    for (let i = 0; i < files.length; i++) {
      formData.append(`files[${i}]`, files[i]);
    }

    dispatch(
      uploadOriginalPurchaseOrderAction({
        data: formData,
        success(res: DecisionsInterface) {
          onChange(res);
          setTimeout(() => setSubmitting(false), 2000);
        },
      })
    );
  };
  return (
    <Formik
      initialValues={initialValues}
      onSubmit={submit}
      validationSchema={validationSchema}
    >
      {({ handleChange, errors, setValues, isSubmitting }) => (
        <Form className="h-100 d-flex flex-dir-column justify-content-between">
          <span className={cn([Styles.cardText, "fw-bold"])}>As a file</span>
          <UploadFile
            classNames="mt-12 mt-md-14 mb-10 mb-md-3"
            files={files}
            setFiles={setFiles}
            ref={inputFileRef}
            formats={SUPPORTED_FORMATS}
            maxSize={maxMB}
            multiple
            name="file"
            onChange={handleChange}
            error={errors.file}
          />
          <div>
            <button
              className={cn(["form-button", Styles.button])}
              type="submit"
              disabled={isSubmitting || !files[0]}
            >
              <span className="d-none d-md-inline">Upload</span>
              <span className="d-md-none">Submit</span>
            </button>
          </div>
        </Form>
      )}
    </Formik>
  );
};

export default AsFile;
