import React from "react";
import Button from "@modules/ui/forms/Button";
import AddCard from "@components/pages/wallet/dialog/AddCard";
import ModalAddBillingAddress from "@components/pages/wallet/dialog/AddBillingAddress";
import { useDispatch } from "react-redux";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { getPaymentMethods } from "@redux/actions/account-actions/PaymentsActions";
import PaymentCardImage from "@components/common/payment-card-image/PaymentCardImage";
import Link from "next/link";

export const AddNewPaymentMethod: React.FC = () => {
  const user = useSelectorAccount((e) => e.user);
  const paymentMethods = useSelectorAccount((e) => e.payments.methods).filter(
    (elem) => {
      const acceptable = ["Visa", "MasterCard", "Amex", "JCB", "UnionPay"];

      return acceptable.indexOf(elem.name) !== -1;
    }
  );
  console.log({ paymentMethods });
  const dispatch = useDispatch();

  React.useEffect(() => {
    dispatch(getPaymentMethods());
  }, []);
  const [show, setShow] = React.useState(false);
  const [showModalAddBillingAddress, setShowModalAddBillingAddress] =
    React.useState(false);

  function closeModal() {
    setShow(false);
  }

  function openModal() {
    setShow(true);
  }

  function addCreditCardButton() {
    const content = (
      <>
        <span className={"d-none d-md-inline"}>Add a credit or debit card</span>
        <span className={"d-md-none"}>Add credit / debit card</span>
      </>
    );

    if (!user) {
      return (
        <Link href={"/login"}>
          <a className={"text-decoration-none"}>
            <Button>{content}</Button>
          </a>
        </Link>
      );
    }

    return <Button onClick={openModal}>{content}</Button>;
  }

  return (
    <>
      <div
        className={
          "d-block d-md-flex justify-content-between align-items-center gap-5"
        }
      >
        <div className="mb-4 mb-md-0">{addCreditCardButton()}</div>

        <div className={"d-flex flex-column"}>
          <p className={"fs-16 fs-md-18 fs-lg-14"}>
            S3 Stores Inc accepts major credit and debit cards
          </p>

          <div className="d-flex gap-1 flex-wrap">
            {paymentMethods.map((method, i) => (
              <PaymentCardImage key={i} logo={method.logo} name={method.name} />
            ))}
          </div>
        </div>
      </div>

      <AddCard open={show} handleClose={closeModal} />

      <ModalAddBillingAddress
        open={showModalAddBillingAddress}
        handleClose={() => setShowModalAddBillingAddress(false)}
      />
    </>
  );
};
