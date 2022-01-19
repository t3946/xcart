import React from "react";
import PlusPanelButton from "@modules/account/components/common/PlusPanelButton";
import RectangularButton from "@modules/account/components/common/RectangularButton";
import { Formik, Form } from "formik";
import cn from "classnames";
import InnerPage from "@modules/account/components/shared/InnerPage";
import RectangularButtonStyles from "@modules/account/components/common/RectangularButton.module.scss";
import Styles from "@modules/account/components/orders/Decision/StreetAddressRequired/StreetAddressRequired.module.scss";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import { AddressTypeEnum } from "@modules/account/ts/consts/address-type.const";
import { AddAddressForm } from "@modules/account/components/addresses/AddAddressForm";
import { Accordion } from "react-bootstrap";
import { useDispatch } from "react-redux";
import { useDialog } from "@modules/account/hooks/useDialog";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { AddNewAddress } from "@modules/account/components/addresses/AddNewAddress";
import { AddressList } from "@modules/account/components/addresses/AddressList";
import { getTerritory } from "@redux/actions/account-actions/MainActions";
import { getAddresses } from "@redux/actions/account-actions/AddressActions";
import { useRouter } from "next/router";
import Card from "@modules/ui/Card";

const StreetAddressRequired: React.FC<any> = () => {
  const dispatch = useDispatch();
  const router = useRouter();
  const userId = useSelector((e: StoreInterface) => {
    return e.user.user_id;
  });

  React.useEffect(() => {
    dispatch(getTerritory());
    dispatch(getAddresses(userId));
  }, []);
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

  const submit = () => {
    return;
  };
  return (
    <InnerPage
      hatClasses={Styles.header}
      bodyClasses={Styles.decisionPage}
      header={"Street address required"}
    >
      <Formik initialValues={initialValues} onSubmit={submit}>
        {({ values, handleChange, setValues }) => {
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
                      <div>
                        {address.street}, {address.detailed}
                      </div>
                      <div>{address.country.viewValue}</div>
                      <div> Phone number: {address.phone_number}</div>
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
                      <div> Phone number: {address.phone_number}</div>
                    </div>
                  </div>
                </Card>
              );
            }
            return addressList;
          };

          const addAddresClickHandler = () => {
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
                  <PlusPanelButton
                    onClick={addAddresClickHandler}
                    classes={{
                      container: [
                        Styles.address,
                        "justify-content-center",
                        "align-items-center",
                        Styles.cursor_pointer,
                        {
                          [RectangularButtonStyles.container_active]:
                            addAddress,
                        },
                      ],
                      text: Styles.newAddressText,
                    }}
                    text={"Add New Address"}
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
              <button
                type="submit"
                className={cn(
                  Styles.button,
                  "form-button",
                  "w-md-auto",
                  "mx-md-auto",
                  "m-lg-0"
                )}
              >
                Submit
              </button>
            </Form>
          );
        }}
      </Formik>
      <BootstrapDialogHOC
        show={addAddressDialog.open}
        title={"Add new address"}
        onClose={addAddressDialog.handleClose}
      >
        <AddAddressForm
          onCancelClick={() =>
            breakpoint({
              xxl: undefined,
              xl: undefined,
              lg: undefined,
              md: undefined,
              sm: undefined,
              xs: addAddressDialog.handleClose,
            })
          }
        />
      </BootstrapDialogHOC>
    </InnerPage>
  );
};

export default StreetAddressRequired;
