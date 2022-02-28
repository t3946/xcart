import React from "react";
import { CardDialog } from "@modules/account/components/wallet/CardDialog";
import { useDialog } from "@modules/account/hooks/useDialog";
import { useRouter } from "next/router";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import Button from "@modules/ui/forms/Button";
import AddCard from "@components/pages/wallet/dialog/AddCard";
import ModalAddBillingAddress from "@components/pages/wallet/dialog/AddBillingAddress";
import { useDispatch } from "react-redux";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { getPaymentMethods } from "@redux/actions/account-actions/PaymentsActions";
import PaymentCardImage from "@components/common/payment-card-image/PaymentCardImage";

export const AddNewPaymentMethod: React.FC = () => {
  const router = useRouter();
  const addDialog = useDialog();
  const breakpoint = useBreakpoint();
  const paymentMethods = useSelectorAccount((e) => e.payments.methods);
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

  return (
    <div
      className={
        "d-block d-md-flex justify-content-between align-items-center gap-5"
      }
    >
      <div className="mb-4 mb-md-0">
        <Button onClick={openModal}>Add a credit or debit card</Button>
      </div>

      <div className={"d-flex flex-column"}>
        <p>S3 Stores Inc accepts major credit and debit cards</p>

        <div className="d-flex gap-1 flex-wrap">
          {paymentMethods.map((method, i) => (
            <PaymentCardImage key={i} logo={method.logo} name={method.name} />
          ))}
        </div>
      </div>

      <AddCard open={show} handleClose={closeModal} />

      <ModalAddBillingAddress
        open={showModalAddBillingAddress}
        handleClose={() => setShowModalAddBillingAddress(false)}
      />
    </div>
  );
};
