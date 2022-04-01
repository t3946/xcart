import React from "react";
import InnerPage from "@components/common/inner-page/InnerPage";
import { RowInterface } from "@modules/account/components/orders/Decision/TableRow";
import Advice, {
  AdviceTypes,
} from "@modules/account/components/orders/Decision/EstimatedTimeArrival/Advice";
import { Form as RBForm } from "react-bootstrap";
import {Form, Formik, FormikHelpers} from "formik";
import * as yup from "yup";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import { useDispatch } from "react-redux";
import { solveDecisionAction } from "@redux/actions/account-actions/DecisionsActions";
import AlternativeItemsTable from "@modules/account/components/orders/Decision/AlternativeItemsOffer/AlternativeItemsTable";
import Styles from "@modules/account/components/orders/Decision/AlternativeItemsOffer/AlternativeItemsOffer.module.scss";
import Button from "@modules/ui/forms/Button";

const AlternativeItemsOffer: React.FC = (props) => {
  const { decision, onChange } = props;
  const dispatch = useDispatch();
  // mockData
  const productCategories: Record<string, RowInterface[]> = {
    outOfStock: [
      {
        name: "Pearl Couscous 22lb",
        sku: " ABF-10637",
        amount: 1,
        date: "1-Oct-2021",
        image: "",
      },
    ],
    inStock: [
      {
        name: "Near East Couscous (12x10 Oz)",
        sku: " B06706",
        amount: 1,
        image: "",
      },
    ],
  };
  const initialState = {
    comment: decision.options.comment || "",
    advice: decision.options.advice || "",
  };
  const validationSchema = yup.object().shape({
    comment: yup.string(),
    advice: yup.string().required(),
  });

  function buttonTemplate(isSubmitting: boolean) {
    return (
      <Button
        type={"submit"}
        className={"form-button estimate-advise__submit-button w-md-auto"}
        disabled={isSubmitting || decision.solved}
      >
        submit
      </Button>
    );
  }

  async function submit(values: Record<any, any>, helpers: FormikHelpers<any>) {
    helpers.setSubmitting(true);

    dispatch(
      solveDecisionAction({
        data: {
          ...values,
          decision_id: decision.decision_id,
        },
        success() {
          helpers.setSubmitting(false);
        },
      })
    );

    onChange("Decision solved");
  }

  return (
    <InnerPage
      hatClasses={Styles.hat}
      bodyClasses={Styles.pageBody}
      header={"Alternative item(s) offer"}
    >
      <Formik
        initialValues={initialState}
        validationSchema={validationSchema}
        onSubmit={submit}
      >
        {({ values, errors, handleChange, isSubmitting }) => {
          return (
            <Form>
              {!!productCategories.outOfStock.length && (
                <AlternativeItemsTable
                  type="outOfStock"
                  items={productCategories.outOfStock}
                />
              )}
              {!!productCategories.inStock.length && (
                <AlternativeItemsTable
                  type="inStock"
                  items={productCategories.inStock}
                />
              )}
              <div className={"estimated-time-arrival-form-controls"}>
                <div className="mb-18">
                  <b>Please advise</b> if you would like us to
                </div>

                <Advice
                  type={AdviceTypes.replace}
                  className={"advise-list__item"}
                  value={"replace"}
                  name={"advice"}
                  checked={"replace" === values.advice}
                  onChange={handleChange}
                  disabled={isSubmitting}
                />

                <Advice
                  type={AdviceTypes.cancel}
                  className={"advise-list__item"}
                  value={"cancel"}
                  name={"advice"}
                  checked={"cancel" === values.advice}
                  onChange={handleChange}
                  disabled={isSubmitting || decision.solved}
                />

                <RBForm.Group
                  controlId="CommentFormEstimatedTimeArrivedDecision"
                  className={"estimated-time-arrival__comment"}
                >
                  <Label className={"d-block"} optional>
                    Comment
                  </Label>

                  <Input
                    as="textarea"
                    name="comment"
                    value={values.comment}
                    onChange={handleChange}
                    className={"advice-comment"}
                    isInvalid={!!errors.comment}
                    disabled={isSubmitting || decision.solved}
                  />

                  <Feedback type="invalid">{errors.comment}</Feedback>
                </RBForm.Group>

                <div className="estimate-advise-submit-button d-flex justify-content-md-center justify-content-lg-start">
                  {buttonTemplate(isSubmitting)}
                </div>
              </div>
            </Form>
          );
        }}
      </Formik>
    </InnerPage>
  );
};

export default AlternativeItemsOffer;
