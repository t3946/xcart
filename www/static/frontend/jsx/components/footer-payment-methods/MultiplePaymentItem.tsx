import React, {useEffect, useState} from "react";
import {usePopper} from "react-popper";
import cn from "classnames";
import Styles from "@client/jsx/components/footer-payment-methods/MultiplePaymentItem.module.scss";
import PaymentItem from "@client/jsx/components/footer-payment-methods/PaymentItem";
import useCLickListener from "@client/modules/account/hooks/useClickListener";

interface IProps {
  paymentMethod: any;
  paymentChildren: any;
  children: any;
}

const MultiplePaymentItem: React.FC<IProps> = (props) => {
  const { paymentMethod, paymentChildren } = props;
  const [referenceElement, setReferenceElement] = useState(null);
  const [popperElement, setPopperElement] = useState(null);
  const [arrowElement, setArrowElement] = useState(null);
  const {styles, attributes} = usePopper(referenceElement, popperElement, {
    placement: "top-start",
    modifiers: [
      {
        name: 'arrow',
        options: {element: arrowElement},
      },
      {
        name: 'offset',
        options: { offset: [0, 20] }
      }
    ],
  });
  const [isVisible, setIsVisible] = useState(false);
  const clickListener = useCLickListener(() => setIsVisible(false));

  function tooltipTemplate() {
    if (!isVisible) {
      return null;
    }

    const paymentItems = [];

    for (const payment of paymentChildren) {
      paymentItems.push(
        <li className={cn("d-flex", Styles.paymentItem)}>
          <img src={payment.logo} alt={payment.name} />
          <span className={"flex-grow-1 mx-2"}>{payment.name}</span>
          <span className={Styles.shortName}>{payment.short_name}</span>
        </li>
      );
    }

    return (
      <div ref={setPopperElement} style={styles.popper} {...attributes.popper} className={cn([Styles.popper])}>
        <div className={cn(Styles.tooltipContent)}>
          <ul className={cn("list-unstyled m-0", Styles.list)}>{paymentItems}</ul>
        </div>
        <div ref={setArrowElement} style={styles.arrow} className={cn(Styles.popperArrow)} />
      </div>
    );
  }

  useEffect(() => {
    clickListener.startListen();

    return () => {
      clickListener.endListen();
    };
  }, []);

  function open(e) {
    e.stopPropagation();
    setIsVisible(true);
  }

  function close(e) {
    e.stopPropagation();
    setIsVisible(false);
  }

  return (
    <>
      <div ref={setReferenceElement}
           onClick={open}
           onMouseOver={open}
           onMouseOut={close}
           className={"d-inline-block"}
           onTouchStart={open}
           onTouchMove={open}
      >
        <PaymentItem paymentMethod={paymentMethod} isMultiple={true} isOpen={isVisible} />
      </div>

      {tooltipTemplate()}
    </>
  );
};

export default MultiplePaymentItem;