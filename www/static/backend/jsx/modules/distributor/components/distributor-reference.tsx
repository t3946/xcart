import React from "react";
import classNames from "classnames";
import { Accordion, Card } from "react-bootstrap";
import ContextAwareToggle from "./context-aware-toggle";

const DistributorReference: React.FC<any> = (props: any) => {
  const [eventKey, setEventKey] = React.useState("0");
  const [isCurrentEventKey, setIsCurrentEventKey] = React.useState(false);

  // шапка аккордеона
  function hat() {
    return (
      <div className={classNames("accordion-trigger", "row")}>
        <div className="col-2">
          <a
            href={props.distributorsLink}
            onClick={(e) => {
              e.stopPropagation();
            }}
            className={classNames(["common-link", "distributors-link"])}
          >
            Distributors
          </a>
        </div>
        <div className="col-8">
          <h2 className="distributor-reference-header text-center">
            {props.mainInfoTitle}
            {" / "}
            <a
              href={props.lastOrderHistoryLink}
              onClick={(e) => {
                e.stopPropagation();
              }}
              className={classNames(["common-link", "last-order-history-link"])}
            >
              Last 6 months of order history
            </a>
          </h2>
        </div>
        <div className="col-2 position-static">
          <svg className="icon accordion-icon accordion_icon">
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
    <div>
      <Accordion defaultActiveKey="0">
        <Card className={classNames(["border-0", "rounded-0"])}>
          <Card.Header className={classNames(["p-0", "border-0"])}>
            <ContextAwareToggle
              eventKey={eventKey}
              onChange={(newEventKey) => setIsCurrentEventKey(newEventKey)}
            >
              {hat()}
            </ContextAwareToggle>
          </Card.Header>

          <Accordion.Collapse eventKey="0">
            <Card.Body>Hello! I'm the body</Card.Body>
          </Accordion.Collapse>
        </Card>
      </Accordion>
    </div>
  );
};

export default DistributorReference;
