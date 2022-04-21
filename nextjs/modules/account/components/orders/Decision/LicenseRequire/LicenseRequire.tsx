import React from "react";
import Styles from "@modules/account/components/orders/Decision/LicenseRequire/LicenseRequire.module.scss";
import cn from "classnames";
import * as yup from "yup";
import { Form, Formik, FormikHelpers } from "formik";
import EstimatedTimeArrivalTable, {
  TableTypes,
} from "@modules/account/components/orders/Decision/Table";
import validatorMaxFileSize from "@utils/yup/validatorMaxFileSize";
import validatorFileFormat from "@utils/yup/validatorFileFormat";
import { iSentOriginalPurchaseOrderViaFaxAction } from "@redux/actions/account-actions/DecisionsActions";
import { useDispatch } from "react-redux";
import { AxiosResponse } from "axios";
import UploadFile from "@modules/ui/UploadFile";
import SentFiles from "@modules/account/components/orders/Decision/SentFiles";
import Button from "@modules/ui/forms/Button";

interface IProps {
  onChange: (message: string) => any;
  decision: any;
}

const LicenseRequire: React.FC<IProps> = (props: IProps) => {
  const { onChange, decision } = props;
  const initialState = {
    file: null,
  };
  const inputFileRef = React.useRef<HTMLInputElement>();
  const maxMB = 10;
  const SUPPORTED_FORMATS = [
    "image/jpg",
    "image/jpeg",
    "image/png",
    "application/pdf",
  ];
  const dispatch = useDispatch();
  const [files, setFiles] = React.useState<File[]>([]);
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

  const tableRows: any = [];

  for (const group of decision.order.groups) {
    for (const detail of group.details) {
      tableRows.push({
        name: detail.product,
        sku: detail.productcode,
        amount: detail.amount,
      });
    }
  }

  function submit(values: Record<any, any>, actions: FormikHelpers<any>) {
    actions.setSubmitting(true);

    const formData = new FormData();
    const files = inputFileRef.current?.files || [];

    formData.append("files[0]", files[0]);
    formData.append("decision_id", decision.decision_id.toString());

    dispatch(
      iSentOriginalPurchaseOrderViaFaxAction({
        data: formData,
        success(res: AxiosResponse) {
          actions.setSubmitting(false);
        },
      })
    );

    onChange("License sent successfully");
  }

  return (
    <Formik
      initialValues={initialState}
      validationSchema={validationSchema}
      onSubmit={submit}
    >
      {({ handleChange, errors, isSubmitting }) => {
        return (
          <Form>
            <h1 className="decision-inner-header decision__inner-header">
              License required
            </h1>

            <EstimatedTimeArrivalTable
              tableType={TableTypes.licenseRequired}
              items={tableRows}
            />

            <div className={Styles.description}>
              To ship these items we require <b>Physician Rx License.</b>
              <br />
              <br />
              Could you please attach it and click 'Send' button?
            </div>

            <h5 className={cn(["fw-bold", Styles.label])}>Upload a document</h5>

            <UploadFile
              classNames="mt-12 mt-md-14 mb-10 mb-md-3"
              files={files}
              setFiles={setFiles}
              ref={inputFileRef}
              formats={SUPPORTED_FORMATS}
              maxSize={maxMB}
              name="file"
              onChange={handleChange}
              error={errors.file}
              disabled={isSubmitting || decision.solved}
            />

            {!!decision.solved && <SentFiles decision={decision} />}

            <div
              className={cn([
                Styles.form__submitButton,
                "d-flex",
                "justify-content-center",
                "justify-content-lg-start",
                { "d-none": decision.solved },
              ])}
            >
              <Button
                type={"submit"}
                className={cn([
                  "form-button",
                  "w-100",
                  "w-md-auto",
                  Styles.submitButton,
                ])}
                disabled={isSubmitting || decision.solved || files.length === 0}
              >
                Send
              </Button>
            </div>
          </Form>
        );
      }}
    </Formik>
  );
};

export default LicenseRequire;
