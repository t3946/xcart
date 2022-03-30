import React from "react";
import Button from "@modules/ui/forms/Button";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import Link from "next/link";
import ModalAddCard from "@components/pages/wallet/dialog/AddCard";
import ModalAddAddress from "@components/pages/wallet/dialog/AddBillingAddress";
import loadStripe from "@utils/loadStripe";
import {Elements} from "@stripe/react-stripe-js";

const stripePromise = loadStripe();

interface IProps {
  classes?: {
    button?: any;
  };
}

export const AddCreditCardButton: React.FC<IProps> = (props) => {
  const { classes } = props;
  const user = useSelectorAccount((e) => e.user);
  const [show, setShow] = React.useState(false);
  const [showModalAddBillingAddress, setShowModalAddBillingAddress] =
    React.useState(false);
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

  function closeModal() {
    setShow(false);
  }

  function openModal() {
    setShow(true);
  }

  return (
    <>
      <Elements stripe={stripePromise}>
        <Button onClick={openModal} className={classes?.button}>
          {content}
        </Button>

        <ModalAddCard open={show} handleClose={closeModal} />

        <ModalAddAddress
          open={showModalAddBillingAddress}
          handleClose={() => setShowModalAddBillingAddress(false)}
        />
      </Elements>
    </>
  );
};

export default AddCreditCardButton;
