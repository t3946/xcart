import React from "react";
import Styles from "@modules/account/components/orders/Decision/LicenseRequire/LicenseRequire.module.scss";
import cn from "classnames";
import * as yup from "yup";
import { Formik, Form, FormikHelpers } from "formik";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { RowInterface } from "@modules/account/components/orders/Decision/TableRow";
import EstimatedTimeArrivalTable, {
  TableTypes,
} from "@modules/account/components/orders/Decision/Table";
import validatorMaxFileSize from "@utils/yup/validatorMaxFileSize";
import validatorFileFormat from "@utils/yup/validatorFileFormat";
import {
  getEtaProductsAction,
  solveDecisionAction,
} from "@redux/actions/account-actions/DecisionsActions";
import { useDispatch } from "react-redux";
import {AxiosResponse} from "axios";

interface IProps {
  onChange: (decision: DecisionsInterface) => any;
  decision: DecisionsInterface;
}

const LicenseRequire: React.FC<IProps> = (props: IProps) => {
  const { onChange, decision } = props;
  const initialState = {
    file: null,
  };
  const [tableRows, setTableRows] = React.useState<RowInterface[]>([]);
  const inputFileRef = React.useRef<HTMLInputElement>();
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
  const [products, setProducts] = React.useState(null);

  if (products === null) {
    dispatch(
      getEtaProductsAction({
        orderId: decision.order_id,

        success(res) {
          setProducts(res);

          const newTableRows = [];

          res.forEach((value) => {
            const { orderAmount, product } = value;

            newTableRows.push({
              name: product.product,
              sku: product.productcode,
              amount: orderAmount,
              date: null,
            });
          });

          setTableRows(newTableRows);
        },
      })
    );
  }

  function submit(values: Record<any, any>, actions: FormikHelpers<any>) {
    actions.setSubmitting(true);

    const formData = new FormData();
    const files = inputFileRef.current?.files || [];

    for (let i = 0; i < files.length; i++) {
      formData.append("attachments[]", files[i]);
    }

    formData.append("type", decision.type.toString());
    formData.append("decision_id", decision.decision_id.toString());

    dispatch(
      solveDecisionAction({
        data: formData,
        success(res: AxiosResponse) {
          onChange(res);
          actions.setSubmitting(false);
        },
      })
    );
  }

  return (
    <Formik
      initialValues={initialState}
      validationSchema={validationSchema}
      onSubmit={submit}
    >
      {({ handleChange }) => {
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

            <label
              className={cn(["form-button__theme-grey", Styles.buttonUpload])}
            >
              Choose files
              <input
                type="file"
                className="d-none"
                ref={inputFileRef}
                name="file"
                multiple={true}
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
