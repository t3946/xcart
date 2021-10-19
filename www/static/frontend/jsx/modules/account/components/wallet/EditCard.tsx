import React, { useContext } from "react";
import { Button, Grid } from "@material-ui/core";
import { useFormik } from "formik";
import { FormInput } from "../shared/FormInput";
import { FormSelect } from "../shared/FormSelect";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { fillMassToSelect } from "../../utils/fill-mass-to-select";
import { convertDataToEditCardForm } from "../../utils/convert-data-to-edit-card-form";
import { useDispatch, useSelector } from "react-redux";
import { editCard } from "../../../../redux/actions/account-actions/PaymentsActions";
import Store from "@client/jsx/redux/stores/Store";
import { CardHeader } from "./CardHeader";
import { editCardFormValidationSchema } from "../../ts/consts/add-card-form";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import { CardItemDto } from "@client/modules/account/ts/types/wallet.type";
import { onCardActionsEnd } from "@client/modules/account/utils/on-card-actions-end";

interface EditCardProps {
  cardInfo: CardItemDto;
}

export const EditCard: React.FC<EditCardProps> = ({ cardInfo }) => {
  const monthsValues = fillMassToSelect(1, 12);

  const yearsValues = fillMassToSelect(
    new Date().getFullYear(),
    new Date().getFullYear() + 10
  );

  const submitCardFormLoading = useSelector(
    (e: StoreInterface) => e.payments.submitCardFormLoading
  );

  const context = useContext(WalletCardsDialogContext);

  const cardSubmitData = useSelector((e: any) => e.payments.submitFormData);

  const dispatch = useDispatch();

  const formik = useFormik({
    initialValues: convertDataToEditCardForm(cardInfo),
    validationSchema: editCardFormValidationSchema,
    onSubmit: null,
  });

  const getCardAddressInfo = (cardInfo) => {
    const card = { ...cardInfo };
    if (cardSubmitData?.address?.address_id) {
      [card.address] = Store
        .getState()
        .addresses.addressesList.filter(
          (address) => address.address_id === cardSubmitData.address.address_id
        )
        .map((e: any) => {
          return {
            ...e,
            country: e.country.viewValue,
            state: e.state.viewValue,
          };
        });
      return card;
    }
    if (cardSubmitData?.address) {
      card.address = cardSubmitData.address;
    }
    return card;
  };

  const cardInformation = getCardAddressInfo(cardInfo);

  const onSubmit = (values, errors) => {
    if (Object.keys(errors).length) {
      return;
    }
    dispatch(
      editCard(
        {
          ...cardSubmitData,
          card: {
            credit_card_id: values.credit_card_id,
            name: values.name,
            expires: Date.parse(
              new Date(
                values.expiresYear.value,
                values.expiresMonth.value
              ).toString()
            ),
          },
          userId: Store.getState().user.id,
        },
        () => onCardActionsEnd(context.handleClose)
      )
    );
  };

  return (
    <div className="billing-address-container">
      <div className="edit-card-content">
        <div>
          <Grid
            container
            justifyContent="space-between"
            className="edit-card-top-part"
          >
            <Grid container direction="column">
              <div className="wallet-card-content-label label-card-block">
                Payment method
              </div>
              <CardHeader
                cardNumber={cardInfo.card_number}
                cardType={cardInfo.card_type}
                containerClass={"full-width"}
              />
            </Grid>
          </Grid>
          <Grid
            alignContent={"center"}
            justifyContent="space-between"
            container
          >
            <div className="wallet-card-content-label label-card-block">
              Billing address
            </div>
            <div
              className="change-address-btn"
              onClick={() =>
                context.setContent(BillingAddressFormEnum.LIST_ADDRESS)
              }
            >
              Change
            </div>
          </Grid>
          <div className={"address-block"}>
            <div>{cardInformation.address.street} </div>
            <div>{cardInformation.address.state}</div>
            <div> {cardInformation.address.country}</div>
            <div>{cardInformation.address.phone_number}</div>
          </div>
        </div>
        <div>
          <form encType="multipart/form-data">
            <div className="edit-card-from-container">
              <div>
                <div className="wallet-card-content-label">Name</div>
                <FormInput
                  placeholder={"Albert H. Einstein"}
                  value={formik.values.name}
                  name={"name"}
                  errorMessage={formik.errors.name}
                  handleChange={formik.handleChange}
                  touched={formik.touched.name}
                  handleBlur={formik.handleBlur}
                  classes={{
                    input: [
                      "full-width",
                      "edit-card-input",
                      "edit-card-input-card-name",
                    ],
                  }}
                />
              </div>

              <div>
                <div className="wallet-card-content-label">Expiration date</div>
                <div className="edit-card-expirations-container">
                  <div className="edit-card-select-expirations edit-card-select-expirations-months">
                    <FormSelect
                      items={monthsValues}
                      value={formik.values.expiresMonth}
                      classes={{
                        group: "add-card-select-expiration-month, full-width",
                        input: "edit-card-input",
                      }}
                      onClick={(value) =>
                        formik.setFieldValue("expiresMonth", value)
                      }
                      name={"state"}
                      id="edit-card-expiration-month"
                    />
                  </div>
                  <div className="edit-card-select-expirations">
                    <FormSelect
                      items={yearsValues}
                      classes={{
                        group: "add-card-select-expiration-month, full-width",
                        input: "edit-card-input",
                      }}
                      value={formik.values.expiresYear}
                      onClick={(value) =>
                        formik.setFieldValue("expiresYear", value)
                      }
                      name={"state"}
                      id="edit-card-expiration-year"
                    />
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div className="edit-card-btns">
        <Button
          onClick={cardsHandleCancel}
          type={"submit"}
          disabled={submitCardFormLoading}
          className="account-submit-btn account-submit-btn-outline auto-width-button cancel-edit-card-btn"
        >
          Cancel
        </Button>
        <Button
          disabled={submitCardFormLoading}
          onClick={() => onSubmit(formik.values, formik.errors)}
          type={"submit"}
          className="account-submit-btn auto-width-button"
        >
          Save
        </Button>
      </div>
    </div>
  );
};
