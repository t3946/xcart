import React from "react";
import Address from "@components/common/forms/Address";
import Button, {ETheme} from "@modules/ui/forms/Button";

interface IProps {
  onCancel: () => void;
  onSubmitted: () => void;
}

export const AddBillingAddressForm: React.FC<IProps> = (props) => {
  const { onCancel, onSubmitted } = props;

  function formFooter(isSubmitting: boolean) {
    return (
      <div className="billing-address-add-btns">
        <div className="d-flex">
          <Button
            onClick={onCancel}
            theme={ETheme.outlined}
            type={"button"}
            disabled={isSubmitting}
            className={"me-2"}
          >
            back
          </Button>

          <Button disabled={isSubmitting} type={"submit"}>
            use this address
          </Button>
        </div>
      </div>
    );
  }

  return (
    <div className="billing-address-container px-3">
      <div className="dialog-title">Add a billing address</div>

      <Address
        footerTemplate={formFooter}
        onSubmitted={onSubmitted}
        addressType={"billing"}
      />
    </div>
  );
};

export default AddBillingAddressForm;
