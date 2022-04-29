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
  const {paymentMethod, paymentChildren} = props;
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
        options: {offset: [0, 20]}
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
          <img src={payment.logo} alt={payment.name}/>
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
        <div ref={setArrowElement} style={styles.arrow} className={cn(Styles.popperArrow)}/>
      </div>
    );
  }

  useEffect(() => {
    clickListener.startListen();

    return () => {
      clickListener.endListen();
    };
  }, []);

  const [eventsHistory, setEventsHistory] = useState([]);
  const [i, setI] = useState(1);

  function toggle(e) {
    setI(i + 1);
    e.stopPropagation();

    switch (eventsHistory[0]) {
      case "onClick":
        if (eventsHistory[1] === "onMouseOver" && eventsHistory[2] === "onTouchStart") {
          break;
        }

        if (eventsHistory[1] === "onMouseOver" && eventsHistory[2] === "onMouseOut" && eventsHistory[3] === "onTouchStart") {
          break;
        }

        if (eventsHistory[1] === "onTouchStart") {
          break;
        }

        setIsVisible(!isVisible);
        break;

      case "onTouchStart":
        setIsVisible(!isVisible);
        break;

      case "onMouseOver":
        if (eventsHistory[1] === "onTouchStart") {
          break;
        }

        if (eventsHistory[1] === "onMouseOut" && eventsHistory[2] === "onTouchStart") {
          break;
        }

        setIsVisible(true);
        break;

      case "onMouseOut":
        if (eventsHistory[1] === "onTouchStart") {
          break;
        }

        setIsVisible(false);
        break;
    }
  }

  const refPaymentItem = React.useRef(null);

  function checkOnParent(targetElement, findElement) {
    let parent = targetElement;

    while (parent) {
      if (parent === findElement) {
        return true;
      }

      parent = parent.parentNode;
    }

    return false;
  }

  return (
    <>
      <div
        ref={setReferenceElement}
         onClick={(e) => {
           e.stopPropagation();
           eventsHistory.unshift("onClick");
           setEventsHistory(eventsHistory.slice(0, 10));
           toggle(e);
         }}
         onMouseOver={(e) => {
           if (checkOnParent(e.relatedTarget, refPaymentItem.current.base)) {
             return;
           }

           eventsHistory.unshift("onMouseOver");
           setEventsHistory(eventsHistory.slice(0, 10));
           toggle(e);
         }}
         onMouseOut={(e) => {
           if (checkOnParent(e.relatedTarget, refPaymentItem.current.base)) {
             return;
           }

           eventsHistory.unshift("onMouseOut");
           setEventsHistory(eventsHistory.slice(0, 10));
           toggle(e);
         }}
         className={"d-inline-block"}
         onTouchStart={(e) => {
           eventsHistory.unshift("onTouchStart");
           setEventsHistory(eventsHistory.slice(0, 10));
           toggle(e);
         }}
         onTouchMove={(e) => {
           eventsHistory.unshift("onTouchMove");
           setEventsHistory(eventsHistory.slice(0, 10));
           toggle(e);
         }}
      >
        <PaymentItem paymentMethod={paymentMethod} isMultiple={true} isOpen={isVisible} ref={refPaymentItem}/>
      </div>

      {tooltipTemplate()}
    </>
  );
};

export default MultiplePaymentItem;