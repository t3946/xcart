//кнопка знает состояние аккордеона (свёрнут/развёрнут), и передаёт изменённое состояние в основной компонент
import React from "react";
import { AccordionContext, useAccordionToggle } from "react-bootstrap";

const ContextAwareToggle: React.FC<any> = ({
  children,
  eventKey,
  callback,
  onChange,
}) => {
  const currentEventKey = React.useContext(AccordionContext);

  const decoratedOnClick = useAccordionToggle(
    eventKey,
    () => callback && callback(eventKey)
  );

  const isCurrentEventKey = currentEventKey === eventKey;

  onChange(isCurrentEventKey);

  return <div onClick={decoratedOnClick}>{children}</div>;
};

export default ContextAwareToggle;
