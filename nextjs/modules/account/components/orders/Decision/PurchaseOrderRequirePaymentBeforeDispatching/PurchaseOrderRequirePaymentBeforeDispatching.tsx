import React from "react";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder.module.scss";
import cn from "classnames";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { Form, Formik } from "formik";
import * as yup from "yup";
import { useDispatch } from "react-redux";
import {
  solveDecisionAction,
  iSentOriginalPurchaseOrderViaFaxAction,
} from "@redux/actions/account-actions/DecisionsActions";
import Button, { ETheme } from "@modules/ui/forms/Button";
import CardHeader from "@modules/account/components/wallet/CardHeader";
import PaymentSections from "@components/pages/decision/[id]/PaymentSections";
import UploadFile from "@modules/ui/UploadFile";
import validatorMaxFileSize from "@utils/yup/validatorMaxFileSize";
import validatorFileFormat from "@utils/yup/validatorFileFormat";
import SentFiles from "@modules/account/components/orders/Decision/SentFiles";

interface IProps {
  onChange: (message: string) => any;
  decision: DecisionsInterface;
  paypalUrl: string;
  cards: any;
  defaultCardId: any;
}

const PurchaseOrderRequirePaymentBeforeDispatching: React.FC<IProps> = (
  props: IProps
) => {
  const dispatch = useDispatch();
  const { decision, onChange, cards, defaultCardId, paypalUrl } = props;
  const classes = {
    p: [
      "estimate-table-caption",
      "estimate-table__caption",
      Styles.decisionCaption,
    ],
  };
  const maxMB = 10;
  const SUPPORTED_FORMATS = [
    "image/jpg",
    "image/jpeg",
    "image/png",
    "application/pdf",
  ];
  const inputFileRef = React.useRef<HTMLInputElement>();
  const [files, setFiles] = React.useState<File[]>([]);
  const initialState = {
    paymentMethod: "debit",
    billingSameShipping: false,
    cardId: defaultCardId,
    file: "",
  };
  const validationSchema = yup.object().shape({
    paymentMethod: yup.string(),
    cardholderName: yup
      .string()
      .max(40, "Max length is 40 character")
      .min(2, "Min length is 2 character")
      .required("Cardholder name is a required field"),
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

  function submitWithFile(setSubmitting: any) {
    return () => {
      setSubmitting(true);

      const formData = new FormData();

      formData.append("decision_id", decision.decision_id);
      formData.append("action", "file");

      for (let i = 0; i < files.length; i++) {
        formData.append(`files[${i}]`, files[i]);
      }

      dispatch(
        iSentOriginalPurchaseOrderViaFaxAction({
          data: formData,
          success() {
            setSubmitting(false);
          },
        })
      );

      onChange("Decision solved");
    };
  }

  function submitWithoutFile(values: any, action: any, setSubmitting: any) {
    return () => {
      setSubmitting(true);

      const data = {
        decision_id: decision.decision_id,
        action,
      };

      if (action === "pay-by-card") {
        data.cardId = values.cardId;
      }

      dispatch(
        solveDecisionAction({
          data,
          success() {
            setSubmitting(false);
          },
        })
      );

      onChange("Decision solved");
    };
  }

  function paymentSectionTemplate(
    values: any,
    handleChange: any,
    isSubmitting: any,
    setSubmitting: any
  ) {
    if (decision.solved) {
      switch (decision.options.action) {
        case "pay-by-card":
          const card = decision.options.card;
          return (
            <>
              <p className={cn([classes.p, Styles.widthMaxContent])}>
                Paid by credit card.
              </p>
              <div className={Styles.decisionCaption}>
                <CardHeader cardLast4={card.last4} cardType={card.brand} />
              </div>
            </>
          );
        case "pay-by-paypal":
          return (
            <p className={cn([classes.p, "m-0", Styles.widthMaxContent])}>
              Paid by paypal.
            </p>
          );
        case "cancel-order":
          return null;
      }
      return null;
    }

    return (
      <PaymentSections
        cards={cards}
        defaultCardId={defaultCardId}
        checkedValue={values.paymentMethod}
        handleChange={handleChange}
        isSubmitting={isSubmitting}
        setSubmitting={setSubmitting}
        submit={submitWithoutFile}
        values={values}
        paypalUrl={paypalUrl}
      />
    );
  }

  function cancelOrderTemplate(values, isSubmitting, setSubmitting) {
    if (decision.solved) {
      if (decision.options.action === "cancel-order") {
        return (
          <p className={cn([classes.p, "m-0", Styles.widthMaxContent])}>
            Order was cancelled.
          </p>
        );
      } else {
        return null;
      }
    }

    return (
      <>
        <p className={cn([classes.p, "m-0", Styles.widthMaxContent])}>
          Alternatively you can cancel the order.
        </p>

        <div
          className={cn([
            "d-flex",
            "justify-content-center",
            "justify-content-lg-start",
          ])}
        >
          <Button
            type="button"
            onClick={submitWithoutFile(values, "cancel-order", setSubmitting)}
            className={cn([
              "fw-bold",
              "mt-4",
              Styles.button,
              Styles.decision__button,
              Styles.decision__button_cancelOrder,
            ])}
            theme={ETheme.outlined}
            disabled={isSubmitting}
          >
            Cancel order
          </Button>
        </div>
      </>
    );
  }

  function sendFileTemplate(helpers: any) {
    const { handleChange, errors, isSubmitting, setSubmitting } = helpers;

    if (decision.solved === 1) {
      if (decision.options.action === "file") {
        return (
          <p className={cn([classes.p])}>
            <p>
              <b>Uploaded files:</b>
            </p>
            <SentFiles decision={decision} />
          </p>
        );
      }

      return null;
    }

    return (
      <>
        <p
          className={cn([classes.p, Styles.decision__caption_paragraphIndent])}
        >
          Otherwise you can attach your Tax Exempt form to verify your Purchase
          Order request.
        </p>

        <p className={cn([classes.p])}>
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
          />

          <Button
            type="button"
            className={"w-auto"}
            theme={ETheme.outlined}
            disabled={isSubmitting || !files.length}
            onClick={submitWithFile(setSubmitting)}
          >
            upload
          </Button>
        </p>
      </>
    );
  }

  return (
    <>
      <Formik initialValues={initialState} validationSchema={validationSchema}>
        {({ values, handleChange, errors, isSubmitting, setSubmitting }) => {
          return (
            <Form>
              <h1
                className={cn([
                  "decision-inner-header",
                  Styles.decision__title,
                ])}
              >
                Purchase Order require payment before dispatching
              </h1>

              {!decision.solved && (
                <>
                  <p
                    className={cn([
                      classes.p,
                      Styles.decision__caption_lineIndent,
                    ])}
                  >
                    According to our company's policy Purchase Orders (POs) are
                    allowed for schools, universities, and governmental bodies
                    only.
                  </p>
                  <p
                    className={cn([
                      classes.p,
                      Styles.decision__caption_paragraphIndent,
                    ])}
                  >
                    Unfortunately, your order does not fall under any of the
                    above categories and we ask you to pay for it by credit card
                    before we can process it.
                  </p>
                </>
              )}
              {paymentSectionTemplate(
                values,
                handleChange,
                isSubmitting,
                setSubmitting
              )}

              {sendFileTemplate({
                handleChange,
                errors,
                isSubmitting,
                setSubmitting,
              })}

              {cancelOrderTemplate(values, isSubmitting, setSubmitting)}
            </Form>
          );
        }}
      </Formik>
    </>
  );
};

export default PurchaseOrderRequirePaymentBeforeDispatching;
