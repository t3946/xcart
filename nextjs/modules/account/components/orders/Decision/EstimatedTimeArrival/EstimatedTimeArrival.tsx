import React from "react";
import EstimatedTimeArrivalTable, {
  TableTypes,
} from "@modules/account/components/orders/Decision/Table";
import * as yup from "yup";
import { Form, Formik, FormikHelpers } from "formik";
import { Form as RBForm } from "react-bootstrap";
import AdviceList from "@modules/account/components/orders/Decision/EstimatedTimeArrival/AdviceList";
import {
  getEtaProductsAction,
  solveDecisionAction,
} from "@redux/actions/account-actions/DecisionsActions";
import { useDispatch } from "react-redux";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { RowInterface } from "@modules/account/components/orders/Decision/TableRow";
import { AxiosResponse } from "axios";
import Button from "@modules/ui/forms/Button";

export enum ECases {
  DISCONTINUED = "001",
  IN_STOCK_OUT_OF_STOCK = "110",
  IN_STOCK_OUT_OF_STOCK_DISCONTINUED = "111",
  OUT_OF_STOCK_DISCONTINUED = "011",
  IN_STOCK_DISCONTINUED = "101",
}

interface IProps {
  onChange: (message: string) => void;
  decision: DecisionsInterface;
}

const EstimatedTimeArrival: React.FC<IProps> = (props: IProps) => {
  const { onChange, decision } = props;
  const dispatch = useDispatch();

  const initialState = {
    comment: (decision.solved && decision?.options?.comment) || "",
    action: (decision.solved && decision?.options?.action) || "",
  };

  const validationSchema = yup.object().shape({
    comment: yup.string(),
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

  const productCategories: Record<string, RowInterface[]> = {
    inStock: [],
    outOfStock: [],
    discontinued: [],
  };

  const [products, setProducts] = React.useState(null);

  if (products === null) {
    dispatch(
      getEtaProductsAction({
        orderId: decision.order_id,

        success(res: AxiosResponse) {
          setProducts(res);
        },
      })
    );
  } else {
    products.forEach((value: any) => {
      const { orderAmount, product } = value;
      const outOfStockItemsNumber = Math.max(0, orderAmount - product.avail);
      const tableRow = {
        name: product.product,
        sku: product.productcode,
        amount: null,
        date: null,
      };

      if (outOfStockItemsNumber === 0) {
        tableRow.amount = orderAmount;
        productCategories.inStock.push({ ...tableRow });
      } else {
        tableRow.amount = outOfStockItemsNumber;

        if (value.estimateTimeArrival) {
          const date = new Date(value.estimateTimeArrival.date);
          const day = date.getDate();
          const month = date.toLocaleDateString("en-US", { month: "short" });
          const year = date.getFullYear();

          tableRow.date = [day, month, year].join("-");
          productCategories.outOfStock.push({ ...tableRow });
        } else {
          productCategories.discontinued.push({ ...tableRow });
        }

        tableRow.amount = orderAmount - outOfStockItemsNumber;
        productCategories.inStock.push({ ...tableRow });
      }
    });
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
                <EstimatedTimeArrivalTable
                  tableType={TableTypes.inStock}
                  items={productCategories.inStock}
                  key={"inStock"}
                />
              )}

              {!!productCategories.outOfStock.length && (
                <EstimatedTimeArrivalTable
                  tableType={TableTypes.outOfStock}
                  items={productCategories.outOfStock}
                  key={"outOfStock"}
                />
              )}

              {!!productCategories.discontinued.length && (
                <EstimatedTimeArrivalTable
                  tableType={TableTypes.discontinued}
                  items={productCategories.discontinued}
                  key={"discontinued"}
                />
              )}

              <div className={"estimated-time-arrival-form-controls"}>
                <div className={"fw-normal form-input-label"}>
                  <b>Please advise</b> if you would like us to
                </div>

                <AdviceList
                  name={"action"}
                  onChange={handleChange}
                  value={values.action}
                  caseCode={caseCode}
                  className={"estimated-time-arrival__advices-list"}
                  disabled={isSubmitting || decision.solved === 1}
                />

                <RBForm.Group
                  controlId="CommentFormEstimatedTimeArrivedDecision"
                  className={"estimated-time-arrival__comment"}
                >
                  <RBForm.Label className="form-input-label form-input-label__optional">
                    Comment
                  </RBForm.Label>

                  <RBForm.Control
                    as="textarea"
                    name="comment"
                    value={values.comment}
                    onChange={handleChange}
                    className={"advice-comment form-input"}
                    isInvalid={!!errors.comment}
                    disabled={isSubmitting || decision.solved === 1}
                  />

                  <RBForm.Control.Feedback type="invalid">
                    {errors.comment}
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
