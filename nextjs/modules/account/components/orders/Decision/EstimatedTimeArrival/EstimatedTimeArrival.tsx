import React from "react";
import EstimatedTimeArrivalTable, {
  TableTypes,
} from "@modules/account/components/orders/Decision/Table";
import * as yup from "yup";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import AdviceList from "@modules/account/components/orders/Decision/EstimatedTimeArrival/AdviceList";
import {
  getEtaProductsAction,
  solveDecisionAction,
} from "@redux/actions/account-actions/DecisionsActions";
import { useDispatch } from "react-redux";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { RowInterface } from "@modules/account/components/orders/Decision/TableRow";

interface IProps {
  onChange: (decision: DecisionsInterface) => any;
  decision: DecisionsInterface;
}

const EstimatedTimeArrival: React.FC<IProps> = (props: IProps) => {
  const { onChange, decision } = props;
  const dispatch = useDispatch();

  const initialState = {
    comment: decision.options.comment || "",
    advice: decision.options.advice || "",
  };

  const validationSchema = yup.object().shape({
    comment: yup.string(),
    advice: yup.string().required(),
  });

  function submit(values, { setSubmitting }) {
    setSubmitting(false);
    dispatch(
      solveDecisionAction({
        data: {
          type: decision.type,
          decision_id: decision.decision_id,
          options: values,
        },
        success(res: DecisionsInterface) {
          onChange(res);
          setSubmitting(false);
        },
      })
    );
  }

  function buttonTemplate(isSubmitting: boolean) {
    if (decision.solved) {
      return;
    }

    return (
      <button
        className={"form-button estimate-advise__submit-button w-md-auto"}
        disabled={isSubmitting}
      >
        submit my decision
      </button>
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

        success(res) {
          setProducts(res);
        },
      })
    );
  } else {
    products.forEach((value) => {
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

  return (
    <div>
      <Formik
        initialValues={initialState}
        validationSchema={validationSchema}
        onSubmit={submit}
      >
        {({ isSubmitting, handleChange, values, errors }) => {
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
                  name={"advice"}
                  onChange={handleChange}
                  value={values.advice}
                  hasInStock={true}
                  hasOutOfStock={true}
                  hasDiscontinued={true}
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
                  {buttonTemplate(isSubmitting)}
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
