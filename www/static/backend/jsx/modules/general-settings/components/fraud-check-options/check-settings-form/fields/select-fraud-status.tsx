import React from "react";
import { Col, Form } from "react-bootstrap";
import { FraudStatus } from "@admin/modules/general-settings/ts/types/fraud-check/data";
interface ISelectFraudStatus {
  name: string;
  state: { get: any; set: any };
  list: FraudStatus[];
}
export const SelectFraudStatus: React.FC<ISelectFraudStatus> = ({
  name,
  state,
  list,
}) => {
  return (
    <Col sm={10}>
      <Form.Control
        value={state.get}
        name={name}
        as="select"
        onChange={state.set}
      >
        {list.map((item) => (
          <option value={item.code}>{item.name}</option>
        ))}
      </Form.Control>
    </Col>
  );
};
