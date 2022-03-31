import React from "react";
import RectangularButton from "@modules/account/components/common/RectangularButton";
import {Form, Formik, FormikHelpers} from "formik";
import cn from "classnames";
import InnerPage from "@components/common/inner-page/InnerPage";
import Styles
  from "@modules/account/components/orders/Decision/StreetAddressRequired/StreetAddressRequired.module.scss";
import {useDispatch, useSelector} from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import {AddressTypeEnum} from "@modules/account/ts/consts/address-type.const";
import {AddAddressForm} from "@modules/account/components/addresses/AddAddressForm";
import {Accordion} from "react-bootstrap";
import {useDialog} from "@modules/account/hooks/useDialog";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import {getTerritory} from "@redux/actions/account-actions/MainActions";
import {getAddresses} from "@redux/actions/account-actions/AddressActions";
import Card from "@modules/ui/Card";
import {formatPhone} from "@utils/phoneNumber";
import Button from "@modules/ui/forms/Button";
import AddNewAddress from "@modules/account/components/addresses/AddNewAddress";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import {solveDecisionAction} from "@redux/actions/account-actions/DecisionsActions";
import AddressText from "@components/common/address-text/AddressText";

const StreetAddressRequired: React.FC<any> = (props) => {
  const dispatch = useDispatch();
  const userId = useSelector((e: StoreInterface) => {
    return e.user.user_id;
  });
  const addresses = useSelector((e: StoreInterface) => {
    return e.addresses.addressesList?.filter(
      (address) => address.address_type === AddressTypeEnum.SHIPPING
    );
  });
  const addAddressDialog = useDialog();
  const breakpoint = useBreakpoint();
  const [addAddress, setAddAddress] = React.useState<string>("");
  const initialValues = {
    address: null,
  };

  function submit(values: Record<any, any>, actions: FormikHelpers<any>) {
    actions.setSubmitting(true);

    const data = {
      decision_id: props.decision.decision_id,
      addressId: parseInt(values.address),
    };

    dispatch(
      solveDecisionAction({
        data,
        success() {
          props.onChange();
          actions.setSubmitting(false);
        },
      })
    );
  }

  React.useEffect(() => {
    dispatch(getTerritory());
    dispatch(getAddresses(userId));
  }, []);

  return (
    <InnerPage
      hatClasses={Styles.header}
      bodyClasses={Styles.decisionPage}
      header={"Street address required"}
    >
      <Formik initialValues={initialValues} onSubmit={submit}>
        {({ values, handleChange, setValues, isSubmitting }) => {
          const checkedAddress = values.address && parseInt(values.address);
          const AdressesTemplate = (addresses) => {
            const addressList: React.ReactNode[] = [];

            for (const address of addresses) {
              addressList.push(
                <RectangularButton
                  key={address.address_id}
                  onClick={() => setAddAddress("")}
                  classNames={{
                    container: [Styles.address, "d-none", "d-md-flex"],
                    body: Styles.addressBody,
                  }}
                  header={
                    <h4 className={Styles.addressHeader}>
                      {address.full_name}
                    </h4>
                  }
                  body={
                    <div className={cn(Styles.addressBody)}>
                      <AddressText address={address} />
                      <div>
                        {" "}
                        Phone number: {formatPhone(address.phone_number)}
                      </div>
                    </div>
                  }
                  footer={
                    <div
                      className={cn(
                        Styles.addressFooter,
                        "mt-auto",
                        "ms-auto",
                        {
                          [Styles.addressFooter_disabled]:
                            checkedAddress === address.address_id,
                        }
                      )}
                    >
                      {checkedAddress === address.address_id
                        ? "Selected"
                        : "Select"}
                    </div>
                  }
                  radioButton={{
                    checkedValue: checkedAddress,
                    value: address.address_id,
                    name: "address",
                    onChange: handleChange,
                  }}
                />
              );
            }
            return addressList;
          };

          const AddressTemplateMobile = (addresses) => {
            const addressList: React.ReactNode[] = [];

            for (const address of addresses) {
              addressList.push(
                <Card
                  key={address.address_id}
                  classes={{
                    card: "d-md-none",
                    cardBody: Styles.addressCard_mobile,
                  }}
                  radioButton={{
                    checkedValue: checkedAddress,
                    value: address.address_id,
                    name: "address",
                    onChange: handleChange,
                    disabled: false,
                  }}
                >
                  <div>
                    <h4 className={Styles.addressHeader}>
                      {address.full_name}
                    </h4>
                    <div className={cn(Styles.addressBody)}>
                      <div>
                        {address.street}, {address.detailed}
                      </div>
                      <div>{address.country.viewValue}</div>
                      <div>
                        {" "}
                        Phone number: {formatPhone(address.phone_number)}
                      </div>
                    </div>
                  </div>
                </Card>
              );
            }
            return addressList;
          };

          const addAddressClickHandler = () => {
            setValues({ address: null });

            breakpoint({
              xxl: undefined,
              xl: undefined,
              lg: function () {
                setAddAddress((prevstate) => (prevstate === "1" ? "" : "1"));
              },
              md: undefined,
              sm: undefined,
              xs: addAddressDialog.handleClickOpen,
            });
          };

          return (
            <Form>
              <p className={cn(Styles.text, "mb-18", "mb-lg-20")}>
                We can't ship orders to P.O. Box addresses.
                <br />
                <b>
                  PO Box 123 <br />
                  Herndon, VA 22071
                </b>
              </p>
              <p className={cn(Styles.text, Styles.decision__caption)}>
                Please provide us your physical street address.
              </p>
              <Accordion activeKey={addAddress}>
                <div
                  className={cn(
                    Styles.addresses,
                    Styles.decision__addresses,
                    "d-grid"
                  )}
                >
                  <AddNewAddress
                    classes={{ container: "" }}
                    onClick={addAddressDialog.handleClickOpen}
                  />

                  {addresses && AdressesTemplate(addresses)}
                  {addresses && AddressTemplateMobile(addresses)}
                </div>
                <Accordion.Collapse eventKey="1">
                  <>
                    <h1
                      className={cn(
                        Styles.header,
                        Styles.headerText,
                        Styles.form__header,
                        "fw-bold",
                        "pt-0",
                        "ps-0"
                      )}
                    >
                      Add new address
                    </h1>
                    <div
                      className={cn(
                        Styles.addAddressForm,
                        Styles.decision__addAddressForm
                      )}
                    >
                      {addAddress && <AddAddressForm />}
                    </div>
                  </>
                </Accordion.Collapse>
              </Accordion>
              <Button
                type="submit"
                className={cn("w-md-auto", "mx-md-auto", "m-lg-0")}
                disabled={!checkedAddress || isSubmitting}
              >
                Submit
              </Button>
            </Form>
          );
        }}
      </Formik>

      <BootstrapDialogHOC
        show={addAddressDialog.open}
        title={"Add address"}
        onClose={addAddressDialog.handleClose}
        classes={{ modal: Styles.modalWidth, body: Styles.modalBody }}
      >
        <AddAddressForm
          onCancelClick={addAddressDialog.handleClose}
          canBeDefault={false}
        />
      </BootstrapDialogHOC>
    </InnerPage>
  );
};

export default StreetAddressRequired;
