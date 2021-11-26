import React from "react";
import Styles from "@client/jsx/modules/account/components/orders/Decision/LicenseRequire/LicenseRequire.module.scss";
import cn from "classnames";
import * as yup from "yup";
import { Formik, Form } from "formik";
import DecisionsInterface from "@client/modules/account/ts/types/decision";
import { RowInterface } from "@client/modules/account/components/orders/Decision/TableRow";
import EstimatedTimeArrivalTable, {
  TableTypes,
} from "@client/modules/account/components/orders/Decision/Table";
import validatorMaxFileSize from "@client/jsx/utils/yup/validatorMaxFileSize";
import validatorFileFormat from "@client/jsx/utils/yup/validatorFileFormat";
import { uploadLicense } from "@client/jsx/redux/actions/account-actions/DecisionsActions";
import dataURItoBlob from "@client/jsx/utils/dataURItoBlob";
import { useDispatch } from "react-redux";

interface IProps {
  onChange: (decision: DecisionsInterface) => any;
  decision: DecisionsInterface;
}

const LicenseRequire: React.FC<IProps> = (props: IProps) => {
  const mockData: RowInterface[] = [
    {
      sku: "STSB",
      amount: 2,
      name: "banjira Banjira Sitar Sympathetic Bridge",
    },
  ];
  const { onChange, decision } = props;
  const initialState = {
    file: null,
  };
  const inputFileRef = React.useRef<HTMLInputElement>();
  const imageRef = React.useRef<string>();

  const maxMB = 10;
  const SUPPORTED_FORMATS = [
    "image/jpg",
    "image/jpeg",
    "image/png",
    "application/pdf",
  ];
  const dispatch = useDispatch();
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

  function submit(values, { setSubmitting }) {
    setSubmitting(false);

    // if (imageRef.current) {
    const formData = new FormData();
    // const blob = dataURItoBlob(imageRef.current);
    console.log("files", inputFileRef.current.files);
    // const documentFile = new File([blob], "filename");

    formData.append(
      "LicenseRequiredForm[license]",
      inputFileRef.current.files[0]
    );
    formData.append("type", decision.type.toString());
    formData.append("decision_id", decision.decision_id.toString());

    dispatch(
      uploadLicense({
        data: formData,
        success(res: DecisionsInterface) {
          onChange(res);
          setSubmitting(false);
        },
      })
    );
    // }
  }

  return (
    <Formik
      initialValues={initialState}
      validationSchema={validationSchema}
      onSubmit={submit}
    >
      {({ handleChange }) => {
        function inputFileChangeHandler(e) {
          /*          handleChange(e);

          const file = inputFileRef.current.files[0];
          const fr = new FileReader();

          fr.onload = () => {
            if (typeof fr.result === "string") {
              imageRef.current = fr.result;
            }
          };

          if (file) {
            fr.readAsDataURL(file);
          }*/
        }
        return (
          <Form>
            <h1 className="decision-inner-header decision__inner-header">
              License required
            </h1>
            <EstimatedTimeArrivalTable
              tableType={TableTypes.licenseRequired}
              items={mockData}
            />

            <div className={Styles.description}>
              To ship these items we require <b>Physician Rx License.</b>
              <br />
              <br />
              Could you please attach it and click 'Send' button?
            </div>

            <h5 className={cn(["fw-bold", Styles.label])}>Upload a document</h5>

            <label
              className={cn(["form-button__theme-grey", Styles.buttonUpload])}
            >
              Choose file
              <input
                type="file"
                className="d-none"
                ref={inputFileRef}
                name="file"
                onChange={inputFileChangeHandler}
              />
            </label>

            <div
              className={cn([
                Styles.form__submitButton,
                "d-flex",
                "justify-content-center",
                "justify-content-lg-start",
              ])}
            >
              <button
                className={cn([
                  "form-button",
                  "w-100",
                  "w-md-auto",
                  Styles.submitButton,
                ])}
              >
                Send
              </button>
            </div>
          </Form>
        );
      }}
    </Formik>
  );
};

export default LicenseRequire;
