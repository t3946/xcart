import React from "react";
import List from "@modules/account/components/orders/DecisionsPreview/List";
import { Row } from "react-bootstrap";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import cn from "classnames";

const Decisions: React.FC = function () {
  const decisions = useSelectorAccount((state) => state.decisions);
  const classes: Record<string, any> = {
    header: ["decisions-list-header", "decisions-lists__header"],
    list: ["px-0 common-scrollbar"],
    notSolved: {
      header: ["mt-md-0"],
      list: "decisions-lists__required-list",
    },
    solved: {
      header: {
        "mt-md-0": decisions.notSolved.total === 0,
      },
    },
  };
  const headers: Record<string, any> = {
    notSolved: "Order decisions required",
    solved: "Order decisions made",
  };
  const lists = [];

  for (const key of ["notSolved", "solved"]) {
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-ignore
    const { total, items } = decisions[key];

    lists.push(
      <div key={key}>
        <h2
          className={cn(classes.header, classes[key].header)}
          key={`${key}-header`}
        >
          {headers[key]}
        </h2>

        <Row className={"m-sm-0"} key={`${key}-row`}>
          <List
            key={`${key}-list`}
            solved={key === "solved"}
            total={total}
            items={items}
            className={cn(classes.list, classes[key].list)}
          />
        </Row>
      </div>
    );
  }

  return <div className={"decisions-lists"}>{lists}</div>;
};

export default Decisions;
