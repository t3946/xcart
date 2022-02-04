import React from "react";
import { CardDialog } from "@modules/account/components/wallet/CardDialog";
import { useDialog } from "@modules/account/hooks/useDialog";
import { useRouter } from "next/router";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import Button from "@modules/ui/forms/Button";
import AddCard from "@components/pages/wallet/dialog/AddCard";
import ModalAddBillingAddress from "@components/pages/wallet/dialog/AddBillingAddress";

export const AddNewPaymentMethod: React.FC = () => {
  const router = useRouter();
  const addDialog = useDialog();
  const breakpoint = useBreakpoint();
  const [show, setShow] = React.useState(false);
  const [showModalAddBillingAddress, setShowModalAddBillingAddress] =
    React.useState(false);

  function closeModal() {
    setShow(false);
  }

  function openModal() {
    setShow(true);
  }

  return (
    <div
      className={"d-block d-md-flex justify-content-between align-items-center"}
    >
      <Button onClick={openModal} className="w-auto">
        Add a credit or debit card
      </Button>

      <Button
        onClick={() => setShowModalAddBillingAddress(true)}
        className="w-auto"
      >
        Add billing address
      </Button>

      <div className={"d-flex flex-column"}>
        <p>S3 Stores Inc accepts major credit and debit cards</p>

        <ul>
          <li>visa</li>
          <li>mastercard</li>
          <li>amex</li>
          <li>discover</li>
          <li>JCB</li>
          <li>Union Pay</li>
        </ul>
      </div>

      <AddCard open={show} handleClose={closeModal} />

      <ModalAddBillingAddress
        open={showModalAddBillingAddress}
        handleClose={() => setShowModalAddBillingAddress(false)}
      />
    </div>
  );
};
