import React from "react";
import EstimatedTimeArrivalTable, {
  TableTypes,
} from "@client/modules/account/components/orders/Decision/EstimatedTimeArrival/Table";
import * as yup from "yup";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import AdviseList from "@client/modules/account/components/orders/Decision/EstimatedTimeArrival/AdviseList";
import { solveDecisionAction } from "@client/jsx/redux/actions/account-actions/DecisionsActions";
import { useDispatch } from "react-redux";
import DecisionsInterface from "@client/modules/account/ts/types/decision";

interface PropsInterface {
  onChange: (decision: DecisionsInterface) => any;
  decision: DecisionsInterface;
}

const EstimatedTimeArrival: React.FC<PropsInterface> = (
  props: PropsInterface
) => {
  const { onChange, decision } = props;
  const dispatch = useDispatch();
  const mockData = [
    // {
    //   name: "Cyprus Raw Umber Medium 4 Oz Vol",
    //   sku: "461-4210",
    //   amount: 2,
    //   date: "15-Sep-2021",
    // },
    // {
    //   name: "Cyprus Raw Umber Medium 4 Oz Vol",
    //   sku: "461-4210",
    //   amount: 2,
    //   date: "15-Sep-2021",
    // },
    // {
    //   name: "Cyprus Raw Umber Medium 4 Oz Vol",
    //   sku: "461-4210",
    //   amount: 2,
    //   date: "15-Sep-2021",
    // },
  ];

  const initialState = {
    comment: "",
    advice: "",
  };

  const validationSchema = yup.object().shape({
    comment: yup.string(),
    advice: yup.string().required(),
  });

  function submit(values, { setSubmitting }) {
    console.log("submit");
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

  return (
    <div>
      <Formik
        initialValues={initialState}
        validationSchema={validationSchema}
        onSubmit={submit}
        ref={React.useRef()}
      >
        {({ isSubmitting, handleChange, values, errors }) => {
          return (
            <Form>
              <h1 className="decision-inner-header decision__inner-header">
                ETA Decision
              </h1>

              <EstimatedTimeArrivalTable
                tableType={TableTypes.inStock}
                items={mockData}
              />
              <EstimatedTimeArrivalTable
                tableType={TableTypes.outOfStock}
                items={mockData}
              />
              <EstimatedTimeArrivalTable
                tableType={TableTypes.discontinued}
                items={mockData}
              />

              <div className={"estimated-time-arrival-form-controls"}>
                <div className={"fw-normal form-input-label"}>
                  <b>Please advise</b> if you would like us to
                </div>

                <AdviseList
                  name={"advice"}
                  onChange={handleChange}
                  value={values.advice}
                  hasInStock={true}
                  hasOutOfStock={true}
                  hasDiscontinued={true}
                  className={"estimated-time-arrival__advices-list"}
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
                  />

                  <RBForm.Control.Feedback type="invalid">
                    {errors.comment}
                  </RBForm.Control.Feedback>
                </RBForm.Group>

                <div className="estimate-advise-submit-button d-flex justify-content-md-center justify-content-lg-start">
                  <button
                    className={
                      "form-button estimate-advise__submit-button w-md-auto"
                    }
                    disabled={isSubmitting}
                  >
                    submit my decision
                  </button>
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
