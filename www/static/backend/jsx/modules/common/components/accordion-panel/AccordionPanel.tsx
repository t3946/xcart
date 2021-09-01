import React from "react";
import { Accordion, Card } from "react-bootstrap";
import classNames from "classnames";
import ContextAwareToggle from "@admin/modules/common/components/accordion/context-aware-toggle";

const AccordionPanel: React.FC<any> = (props: any) => {
  const [eventKey, setEventKey] = React.useState("0");
  const [isCurrentEventKey, setIsCurrentEventKey] = React.useState(false);

  function hatTemplate() {
    return (
      <div className="accordion-trigger row">
        <div className="col-2">{props.columnLeft}</div>

        <div className="col-8">{props.header}</div>

        <div className="col-2">
          <svg className="accordion-icon accordion_icon">
            <use
              xlinkHref={`/static/frontend/images/icons/sprite.svg#${
                isCurrentEventKey ? "switcher-minus__ash" : "switcher-plus__ash"
              }`}
            />
          </svg>
        </div>
      </div>
    );
  }

  return (
    <Accordion defaultActiveKey="0">
      <Card className={classNames(["border-0", "rounded-0"])}>
        <Card.Header className={classNames(["p-0", "border-0"])}>
          <ContextAwareToggle
            eventKey={eventKey}
            onChange={(newEventKey) => setIsCurrentEventKey(newEventKey)}
          >
            {hatTemplate()}
          </ContextAwareToggle>
        </Card.Header>

        <Accordion.Collapse eventKey="0">
          <Card.Body className={"p-0"}>{props.body}</Card.Body>
        </Accordion.Collapse>
      </Card>
    </Accordion>
  );
};

export default AccordionPanel;
