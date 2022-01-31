import * as React from "react";
import {
  CardElement,
  useStripe,
  useElements,
  CardElementProps,
} from "@stripe/react-stripe-js";
import _merge from "lodash/merge";
import cn from "classnames";
import Styles from "@modules/ui/StripeField.module.scss";
import * as stripeJs from "@stripe/stripe-js";

interface IProps extends CardElementProps {
  afterInit?: (e: any) => any;
  error?: string;
  setError?: (e: string) => void;
  className?: any;
  onReady?: (cardElement: stripeJs.StripeCardElement) => any;
}

const StripeField: React.FC<IProps> = function (props) {
  const stripe = useStripe();
  const elements = useElements();
  const defaultProps = {
    options: {
      classes: {
        base: Styles.stripeElement,
      },
    },
  };

  const cardElementProps = _merge(defaultProps, {
    options: { ...props.options },
  });
  const hasErrors = !!props.error;
  const [isFocus, setIsFocus] = React.useState(false);
  const classes = {
    stripeWrapper: [
      "form-input",
      "form-control",
      props.className,
      "d-flex",
      "align-items-center",
      {
        [Styles.stripe_focus]: isFocus,
        [Styles.stripe_focus_isInvalid]: isFocus && hasErrors,
        "is-invalid": hasErrors,
      },
    ],
  };

  React.useEffect(function () {
    props.afterInit &&
      props.afterInit({
        stripe,
        elements,
      });
  });

  return (
    <div className={cn(classes.stripeWrapper)}>
      <CardElement
        {...cardElementProps}
        onChange={(e) => {
          const errorMessage = e.error ? e.error.message : "";

          props.setError && props.setError(errorMessage);
        }}
        onReady={props.onReady}
        onFocus={() => setIsFocus(true)}
        onBlur={async () => setIsFocus(false)}
      />
    </div>
  );
};

export default StripeField;
