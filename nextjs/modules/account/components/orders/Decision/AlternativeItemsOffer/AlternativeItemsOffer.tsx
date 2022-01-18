import React from "react";
import InnerPage from "@modules/account/components/shared/InnerPage";
import { RowInterface } from "@modules/account/components/orders/Decision/TableRow";
import Table, {
  TableTypes,
} from "@modules/account/components/orders/Decision/Table";
import Advice, {
  AdviceTypes,
} from "@modules/account/components/orders/Decision/EstimatedTimeArrival/Advice";
import { Form as RBForm } from "react-bootstrap";
import { Formik, Form } from "formik";
import * as yup from "yup";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import { useDispatch } from "react-redux";
import { submitAlternativeItemsOffer } from "@redux/actions/account-actions/DecisionsActions";

import Styles from "@modules/account/components/orders/Decision/AlternativeItemsOffer/AlternativeItemsOffer.module.scss";

const AlternativeItemsOffer: React.FC = () => {
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
    comment: "",
    advice: "",
  };

  const validationSchema = yup.object().shape({
    comment: yup.string(),
    advice: yup.string().required(),
  });

  function buttonTemplate(isSubmitting: boolean) {
    // if (decision.solved) {
    //   return;
    // }

    return (
      <button
        className={"form-button estimate-advise__submit-button w-md-auto"}
        disabled={isSubmitting}
      >
        submit my decision
      </button>
    );
  }

  function submit(values, { setSubmitting }) {
    setSubmitting(false);
    dispatch(
      submitAlternativeItemsOffer({
        data: {
          // type: decision.type,
          // decision_id: decision.decision_id,
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
                <Table
                  tableType={TableTypes.alternativeItemsOfferOutOfStock}
                  items={productCategories.outOfStock}
                  key={"outOfStock"}
                />
              )}
              {!!productCategories.inStock.length && (
                <Table
                  tableType={TableTypes.alternativeItemsOfferInStock}
                  items={productCategories.inStock}
                  key={"inStock"}
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
                  disabled={isSubmitting}
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
                    disabled={isSubmitting}
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
