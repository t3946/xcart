/**
 * Компонент для модификации переключателя bootstrap/accordion
 * https://react-bootstrap.github.io/components/accordion/#custom-toggle-with-expansion-awareness
 *
 * Принимает дочерним элементом переключатель, вызывает хук onChange и отправляет туда новое состояние
 * аккордеона (свёрнут/развёрнут)
 */

import React from "react";
import { AccordionContext, useAccordionButton } from "react-bootstrap";

const ContextAwareToggle: React.FC<any> = ({
  children,
  eventKey,
  callback,
  onChange,
}) => {
  const currentEventKey = React.useContext(AccordionContext);

  const decoratedOnClick = useAccordionButton(
    eventKey,
    () => callback && callback(eventKey)
  );

  const isCurrentEventKey = currentEventKey === eventKey;

  onChange(isCurrentEventKey);

  return <div onClick={decoratedOnClick}>{children}</div>;
};

export default ContextAwareToggle;
