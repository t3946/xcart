import * as React from "react";
import Button, { ETheme } from "@modules/ui/forms/Button";
import { addAction } from "@redux/actions/CartActions";
import { useDispatch } from "react-redux";

interface IProps {
  productId: number;
  quantity: number;
}

export const BuyAgainButton: React.FC<IProps> = function (props) {
  const { productId, quantity } = props;
  const dispatch = useDispatch();
  const [submitting, setSubmitting] = React.useState(false);
  const [added, setAdded] = React.useState(false);

  function addToCart() {
    setSubmitting(true);
    const data = {
      productId,
      quantity,
    };

    dispatch(
      addAction({
        data,
        callback() {
          setAdded(true);
          setSubmitting(false);
        },
      })
    );
  }

  if (added) {
    return (
      <a href={"/checkout/shipping/"} className={"text-decoration-none"}>
        <Button className={"w-md-auto"} theme={ETheme.outlined}>
          checkout
        </Button>
      </a>
    );
  }

  return (
    <Button className={"w-md-auto"} onClick={addToCart} disabled={submitting}>
      buy again
    </Button>
  );
};

export default BuyAgainButton;
