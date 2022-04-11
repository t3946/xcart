import React from "react";
import EstimatedTimeArrivalTable, {
  TableTypes,
} from "@modules/account/components/orders/Decision/Table";
import * as yup from "yup";
import { Form, Formik, FormikHelpers } from "formik";
import { Form as RBForm } from "react-bootstrap";
import AdviceList from "@modules/account/components/orders/Decision/EstimatedTimeArrival/AdviceList";
import { solveDecisionAction } from "@redux/actions/account-actions/DecisionsActions";
import { useDispatch } from "react-redux";
import { RowInterface } from "@modules/account/components/orders/Decision/TableRow";
import Button from "@modules/ui/forms/Button";
import Style from "@modules/account/components/orders/Decision/EstimatedTimeArrival/EstimatedTimeArrival.module.scss";

export enum ECases {
  DISCONTINUED = "001",
  IN_STOCK_OUT_OF_STOCK = "110",
  IN_STOCK_OUT_OF_STOCK_DISCONTINUED = "111",
  OUT_OF_STOCK_DISCONTINUED = "011",
  IN_STOCK_DISCONTINUED = "101",
}

interface IProps {
  onChange: (message: string) => void;
  decision: any;
}

const EstimatedTimeArrival: React.FC<IProps> = (props: IProps) => {
  const { onChange, decision } = props;
  const dispatch = useDispatch();
  const initialState = {
    decision_comment: (decision.solved && decision?.options?.decision_comment) || "",
    action: (decision.solved && decision?.options?.action) || "",
  };

  const validationSchema = yup.object().shape({
    decision_comment: yup.string(),
    action: yup.string().required(),
  });

  function submit(values: Record<any, any>, actions: FormikHelpers<any>) {
    actions.setSubmitting(false);
    const data = { ...values, decision_id: decision.decision_id };

    dispatch(
      solveDecisionAction({
        data,
      })
    );

    onChange("Decision solved");
  }

  function buttonTemplate(values, isSubmitting: boolean) {
    if (decision.solved) {
      return;
    }

    return (
      <Button
        className={"estimate-advise__submit-button w-md-auto"}
        disabled={isSubmitting || !values.action}
        type={"submit"}
      >
        submit my decision
      </Button>
    );
  }

  function formatDate(time: number) {
    const date = new Date(time * 1000);

    if (date < new Date()) {
      return "Unknown";
    }

    const day = date.getDate();
    const month = date.toLocaleDateString("en-US", { month: "short" });
    const year = date.getFullYear();

    return [day, month, year].join("-");
  }

  const productCategories: Record<string, RowInterface[]> = {
    inStock: [],
    outOfStock: [],
    discontinued: [],
  };

  for (const group of decision.order.groups) {
    for (const detail of group.details) {
      const product = detail.xcart_products;
      const tableRow: any = {
        name: detail.product,
        sku: detail.productcode,
        amount: null,
        date: null,
      };

      const isActualETADate =
        new Date(product.eta_date_mm_dd_yyyy * 1000) > new Date();

      // discontinued
      if (product.forsale === "N" || (detail.back > 0 && !isActualETADate)) {
        tableRow.amount = detail.back;
        productCategories.discontinued.push({ ...tableRow });
      }

      // out of stock
      if (detail.back > 0 && isActualETADate) {
        tableRow.date = formatDate(product.eta_date_mm_dd_yyyy);
        tableRow.amount = detail.back;
        productCategories.outOfStock.push({ ...tableRow });
      }

      // in stock
      if (detail.items_stock > 0) {
        tableRow.amount = detail.items_stock;
        productCategories.inStock.push({ ...tableRow });
      }
    }
  }

  //get case code
  const caseCode =
    (productCategories.inStock.length ? "1" : "0") +
    (productCategories.outOfStock.length ? "1" : "0") +
    (productCategories.discontinued.length ? "1" : "0");

  if (decision.solved) {
    switch (decision.options.action) {
      case "acknowledged":
      case "cancel-order":
        return <p>Order was canceled.</p>;
    }

    switch (caseCode) {
      case ECases.DISCONTINUED:
        initialState.action = "acknowledged";
        break;
    }
  }

  return (
    <div>
      <Formik
        initialValues={initialState}
        validationSchema={validationSchema}
        onSubmit={submit}
      >
        {({ isSubmitting, handleChange, values, errors }) => {
          if (caseCode === ECases.DISCONTINUED) {
            return (
              <Form>
                <p className={"ps-lg-4"}>
                  All items you ordered are currently discontinued / 'out of
                  stock' without definite re-stocking date:
                </p>

                <EstimatedTimeArrivalTable
                  tableType={TableTypes.discontinued}
                  items={productCategories.discontinued}
                  key={"discontinued"}
                />

                <div className={"ps-lg-4"}>
                  <p>
                    Thus, we had to cancel your order and void the transaction.
                  </p>

                  <Button
                    type={"submit"}
                    className={"w-auto"}
                    disabled={isSubmitting}
                  >
                    acknowledged
                  </Button>
                </div>
              </Form>
            );
          }

          return (
            <Form>
              <h1 className="decision-inner-header decision__inner-header">
                ETA Decision
              </h1>

              {!!productCategories.inStock.length && (
                <>
                  <p className={Style.tableCaption}>
                    The items listed below are currently 'in stock':
                  </p>
                  <EstimatedTimeArrivalTable
                    tableType={TableTypes.inStock}
                    items={productCategories.inStock}
                    key={"inStock"}
                  />
                </>
              )}

              {!!productCategories.outOfStock.length && (
                <>
                  <p className={Style.tableCaption}>
                    The following items on your order are currently 'out of
                    stock'.
                  </p>
                  <p className={Style.tableCaption}>
                    ETA date(s) are shown below:
                  </p>
                  <EstimatedTimeArrivalTable
                    tableType={TableTypes.outOfStock}
                    items={productCategories.outOfStock}
                    key={"outOfStock"}
                  />
                </>
              )}

              {!!productCategories.discontinued.length && (
                <>
                  <p className={Style.tableCaption}>
                    All items you ordered are currently discontinued / 'out of
                    stock' without definite re-stocking date:
                  </p>
                  <EstimatedTimeArrivalTable
                    tableType={TableTypes.discontinued}
                    items={productCategories.discontinued}
                    key={"discontinued"}
                  />
                </>
              )}

              <div className={Style.form}>
                <div className={"fw-normal form-input-label mb-18 mb-md-4"}>
                  <b>Please advise</b> if you would like us to
                </div>

                <AdviceList
                  name={"action"}
                  onChange={handleChange}
                  value={values.action}
                  caseCode={caseCode}
                  disabled={isSubmitting || decision.solved === 1}
                />

                <RBForm.Group
                  controlId="CommentFormEstimatedTimeArrivedDecision"
                  className={"estimated-time-arrival__comment"}
                >
                  <RBForm.Label className="form-input-label form-input-label__optional mb-18 mb-md-4 d-inline-block">
                    Comment
                  </RBForm.Label>

                  <RBForm.Control
                    as="textarea"
                    name="decision_comment"
                    value={values.decision_comment}
                    onChange={handleChange}
                    className={"advice-comment form-input"}
                    isInvalid={!!errors.decision_comment}
                    disabled={isSubmitting || decision.solved === 1}
                  />

                  <RBForm.Control.Feedback type="invalid">
                    {errors.decision_comment}
                  </RBForm.Control.Feedback>
                </RBForm.Group>
                <div className="estimate-advise-submit-button d-flex justify-content-md-center justify-content-lg-start">
                  {buttonTemplate(values, isSubmitting)}
                </div>
              </div>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};

export default EstimatedTimeArrival;
