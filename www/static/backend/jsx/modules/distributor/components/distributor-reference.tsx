import React from "react";
import classNames from "classnames";
import { Accordion, Card } from "react-bootstrap";
import ContextAwareToggle from "./context-aware-toggle";
import appData from "@admin/utils/app-data";

const DistributorReference: React.FC<any> = (props: any) => {
  const [eventKey, setEventKey] = React.useState("0");
  const [isCurrentEventKey, setIsCurrentEventKey] = React.useState(false);

  // шапка аккордеона
  function hatTemplate() {
    return (
      <div className="accordion-trigger row">
        <div className="col-2">
          <a
            href={appData().distributor.reference.distributorsLink}
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
            {appData().distributor.reference.mainInfoTitle}
            {" / "}
            <a
              href={appData().distributor.reference.lastOrderHistoryLink}
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

  function summaryOptionTemplate(name: string, value: string) {
    if (value) {
      return (
        <li>
          <b>{name}: </b>
          {value}
        </li>
      );
    }
  }

  function callButtonTemplate() {
    const { isGoodTimeToSendEmail, normalizedPhone } = appData().distributor.reference;

    if (!normalizedPhone) {
      return;
    }

    return (
      <div
        className={classNames({
          call_btn_distr_a: isGoodTimeToSendEmail,
          call_btn_distr_d: !isGoodTimeToSendEmail,
        })}
      >
        <a target="_blank" href={`tel:${normalizedPhone}`}>
          <div style="width: 219px; height: 44px;"></div>
        </a>
      </div>
    );
  }

  function sectionsListLinksTemplate(links) {
    const templates = [];

    for (const link of links) {
      if (appData().distributor.reference.currentSectionKey === link.key) {
        templates.push(
          <li className={"section-link-item"}>
            <b>{link.title}</b>
          </li>
        );
      } else {
        templates.push(
          <li className={"section-link-item"}>
            <a
              href={link.url}
              className={classNames([
                "section-link",
                { "section-link_required": link.required },
              ])}
            >
              {link.title}
            </a>
          </li>
        );
      }
    }

    return (
      <ul
        className={classNames(["list-unstyled", "distributor_section-links"])}
      >
        {templates}
      </ul>
    );
  }

  function sectionsListTemplate(sections) {
    const templates = [];

    for (let i = 0; i < sections.length; i++) {
      templates.push(
        <li>
          <div
            className={classNames([
              "distributor-section-title",
              "distributor-section_title",
            ])}
          >
            <b>{sections[i].name}</b>
          </div>
          {sectionsListLinksTemplate(sections[i].sub_sections)}
        </li>
      );
    }

    return templates;
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
              {hatTemplate()}
            </ContextAwareToggle>
          </Card.Header>

          <Accordion.Collapse eventKey="0">
            <Card.Body className={"p-0"}>
              <div className="summary">
                <div className="row distributor_row-layout">
                  <div className="col-6 distributor-layout-column">
                    <p className={"m-0"} dangerouslySetInnerHTML={{__html: appData().distributor.reference.description}} />
                  </div>

                  <div className="col-6 distributor-layout-column">
                    <ul
                      className={classNames([
                        "list-unstyled",
                        "summary-options",
                        "summary_options",
                      ])}
                    >
                      {summaryOptionTemplate(
                        "Distributor time",
                        appData().distributor.reference.time
                      )}
                      {summaryOptionTemplate(
                        "Distributor phone",
                        appData().distributor.reference.phone
                      )}
                    </ul>
                    {callButtonTemplate()}
                  </div>
                </div>
              </div>

              <div className={"distributor-sections"}>
                <div className="row distributor_row-layout">
                  <div className="col-6 distributor-layout-column">
                    <ul className="list-unstyled m-0">
                      {sectionsListTemplate(
                        appData().distributor.sections.slice(0, 4)
                      )}
                    </ul>
                  </div>

                  <div className="col-6 distributor-layout-column">
                    <ul className="list-unstyled m-0">
                      {sectionsListTemplate(
                        appData().distributor.sections.slice(4)
                      )}
                    </ul>
                  </div>
                </div>
              </div>
            </Card.Body>
          </Accordion.Collapse>
        </Card>
      </Accordion>
    </div>
  );
};

export default DistributorReference;
