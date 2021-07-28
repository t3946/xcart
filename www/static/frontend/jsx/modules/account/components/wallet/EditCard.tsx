import React, { useContext } from "react";
import { Button, Grid } from "@material-ui/core";
import { Form, Formik } from "formik";
import {
  addAddressFormValidationSchema,
  initialAddAddressFormValue,
} from "../../ts/consts/add-address-form";
import { FormInput } from "../shared/FormInput";
import { FormSelect } from "../shared/FormSelect";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";

export const EditCard = () => {
  const context = useContext(WalletCardsDialogContext);
  return (
    <div className="billing-address-container">
      <Grid container justify="space-between" className="edit-card-top-part">
        <Grid xs={4} container direction="column">
          <div className="wallet-card-content-label label-card-block">
            Payment method
          </div>
          <div className="wallet-card-name wallet-card-name-header full-width">
            <img
              className="wallet-card-img"
              src={`/static/frontend/dist/images/icons/account/cards/visa.svg`}
            />
            <div>Mastercard ending in 1234</div>
          </div>
        </Grid>
        <Grid xs={8}>
          <Formik
            initialValues={initialAddAddressFormValue}
            onSubmit={null}
            validationSchema={addAddressFormValidationSchema}
          >
            {({
              errors,
              setFieldValue,
              values,
              touched,
              handleChange,
              handleBlur,
            }) => {
              return (
                <Form encType="multipart/form-data">
                  <Grid justify="space-between" container>
                    <Grid xs={3}>
                      <div className="wallet-card-content-label">
                        Card number
                      </div>
                      <FormInput
                        placeholder={"Albert H. Einstein"}
                        value={values.full_name}
                        name={"full_name"}
                        errorMessage={errors.full_name}
                        handleChange={handleChange}
                        touched={touched.full_name}
                        handleBlur={handleBlur}
                        classes={{
                          input: ["full-width", "edit-card-input"],
                        }}
                      />
                    </Grid>

                    <Grid xs={7}>
                      <div className="wallet-card-content-label">
                        Expiration date
                      </div>
                      <Grid container>
                        <Grid xs={5}>
                          <FormSelect
                            items={[]}
                            value={values.state}
                            classes={{
                              group: "add-card-select-expiration-month",
                              input: "edit-card-input",
                            }}
                            onClick={(value) => setFieldValue("state", value)}
                            name={"state"}
                          />
                        </Grid>
                        <Grid xs={4}>
                          <FormSelect
                            items={[]}
                            classes={{
                              group: "add-card-select-expiration-month",
                              input: "edit-card-input",
                            }}
                            value={values.state}
                            onClick={(value) => setFieldValue("state", value)}
                            name={"state"}
                          />
                        </Grid>
                      </Grid>
                    </Grid>
                  </Grid>
                </Form>
              );
            }}
          </Formik>
        </Grid>
      </Grid>
      <Grid alignContent={"center"} justify="space-between" container xs={3}>
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
        <div> 27 Joseph St. </div>
        <div> Chatham, ON, N7L 3G4 </div>
        <div> Canada</div>
        <div> (763) 635-4364</div>
      </div>
      <Grid container xs={3} justify="space-between">
        <Button
          onClick={() => context.handleClose()}
          type={"submit"}
          className="account-submit-btn account-submit-btn-outline auto-width-button"
        >
          Cancel
        </Button>
        <Button
          type={"submit"}
          className="account-submit-btn auto-width-button"
        >
          Save
        </Button>
      </Grid>
    </div>
  );
};
