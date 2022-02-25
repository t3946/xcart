import React from "react";
import { Formik, Form } from "formik";
import { convertDataToEditCardForm } from "../../utils/convert-data-to-edit-card-form";
import { useDispatch } from "react-redux";
import { changeCardHolderName } from "@redux/actions/account-actions/PaymentsActions";
import { CardHeader } from "./CardHeader";
import { editCardFormValidationSchema } from "../../ts/consts/add-card-form";
import { Card as ICard } from "@stripe/stripe-js";
import { addressToString } from "@components/pages/wallet/Card";
import Feedback from "@modules/ui/forms/Feedback";
import Input from "@modules/ui/forms/Input";

interface EditCardProps {
  cardInfo: ICard;
  changeAddress: () => void;
  onCancel: () => void;
}

export const EditCard: React.FC<EditCardProps> = ({
  cardInfo,
  changeAddress,
  onCancel,
}) => {
  const dispatch = useDispatch();

  function onSubmit(values) {
    dispatch(
      changeCardHolderName({
        cardHolderName: values.cardHolderName,
        cardId: cardInfo.id,
        success() {
          window.location.reload();
        },
      })
    );
  }

  return (
    <Formik
      initialValues={convertDataToEditCardForm(cardInfo)}
      validationSchema={editCardFormValidationSchema}
      onSubmit={onSubmit}
    >
      {({ values, errors, isSubmitting, handleChange, touched }) => {
        return (
          <Form>
            <div className="billing-address-container">
              <div className="edit-card-content">
                <div>
                  <div className="edit-card-top-part">
                    <div className="d-flex flex-dir-column">
                      <div className="wallet-card-content-label label-card-block">
                        Payment method
                      </div>
                      <CardHeader
                        cardLast4={cardInfo.last4}
                        cardType={cardInfo.brand}
                        containerClass={"full-width"}
                      />
                    </div>
                  </div>
                  <div className="d-flex align-center justify-content-between">
                    <div className="wallet-card-content-label label-card-block">
                      Billing address
                    </div>
                    <div className="change-address-btn" onClick={changeAddress}>
                      Change
                    </div>
                  </div>
                  <div className={"address-block"}>
                    <div>{addressToString(cardInfo.metadata.address)} </div>
                  </div>
                </div>
                <div className="col">
                  <div className="edit-card-from-container justify-content-between">
                    <div className="col-lg-8">
                      <div className="wallet-card-content-label">
                        Name on card
                      </div>

                      <Input
                        placeholder={"Albert H. Einstein"}
                        value={values.cardHolderName}
                        name={"cardHolderName"}
                        onChange={handleChange}
                      />

                      <Feedback
                        type="invalid"
                        className="d-block position-absolute"
                      >
                        {!!touched.cardHolderName && errors.cardHolderName}
                      </Feedback>
                    </div>
                  </div>
                </div>
              </div>

              <div className="edit-card-btns">
                <button
                  onClick={onCancel}
                  type={"button"}
                  disabled={isSubmitting}
                  className="form-button account-submit-btn account-submit-btn-outline auto-width-button cancel-edit-card-btn"
                >
                  Cancel
                </button>
                <button
                  disabled={isSubmitting}
                  type={"submit"}
                  className="form-button account-submit-btn auto-width-button"
                >
                  Save
                </button>
              </div>
            </div>
          </Form>
        );
      }}
    </Formik>
  );
};
