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
import { editCard } from "../../../../redux/actions/account-actions/WalletActions";
import { accountStore } from "../../../../redux/stores/StoreAccount";
import { useHistory } from "react-router";
import { CardHeader } from "./CardHeader";
import { editCardFormValidationSchema } from "../../ts/consts/add-card-form";

export const EditCard = ({ cardInfo }) => {
  const monthsValues = fillMassToSelect(1, 12);

  const yearsValues = fillMassToSelect(
    new Date().getFullYear(),
    new Date().getFullYear() + 10
  );

  const submitCardFormLoading = useSelector(
    (e: any) => e.wallet.submitCardFormLoading
  );

  const history = useHistory();

  const context = useContext(WalletCardsDialogContext);

  const cardSubmitData = useSelector((e: any) => e.wallet.submitFormData);

  const dispatch = useDispatch();

  const formik = useFormik({
    initialValues: convertDataToEditCardForm(cardInfo),
    validationSchema: editCardFormValidationSchema,
    onSubmit: null,
  });

  const getCardAddressInfo = (cardInfo) => {
    const card = { ...cardInfo };
    if (cardSubmitData?.address?.address_id) {
      card.address = accountStore
        .getState()
        .addresses.addressesList.filter(
          (address) =>
            address.addresses_id === cardSubmitData.address.address_id
        )
        .map((e: any) => {
          return {
            ...e,
            country: e.country.viewValue,
            state: e.state.viewValue,
          };
        })[0];
      return card;
    }
    if (cardSubmitData?.address) {
      card.address = cardSubmitData.address;
    }
    return card;
  };

  const onEditEnd = () => {
    if (accountStore.getState().main.breakpoint.is768) {
      history.push("/account/payments/wallet");
      return;
    }

    context.handleClose();
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
          userId: accountStore.getState().user.id,
        },
        onEditEnd
      )
    );
  };

  return (
    <div className="billing-address-container">
      <div className="edit-card-content">
        <div>
          <Grid
            container
            justify="space-between"
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
          <Grid alignContent={"center"} justify="space-between" container>
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
          onClick={onEditEnd}
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
