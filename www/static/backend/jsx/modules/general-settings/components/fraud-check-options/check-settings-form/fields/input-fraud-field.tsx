import React from "react";
import { Col, Form } from "react-bootstrap";
interface InputFraudField {
  state: { get: string | number; set: (event) => void };
  name: string;
}
export const InputFraudField: React.FC<InputFraudField> = ({ state, name }) => {
  return (
    <Col sm={10}>
      <Form.Control
        onChange={state.set}
        value={state.get}
        name={name}
        type="text"
      />
    </Col>
  );
};
