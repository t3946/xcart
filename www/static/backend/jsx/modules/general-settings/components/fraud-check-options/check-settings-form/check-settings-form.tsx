import React, { Fragment, useState } from "react";
import { Grid, Typography } from "@material-ui/core";
import { Form, Row, Col, Button } from "react-bootstrap";

export const CheckSettingsForm: React.FC<any> = () => {
  const [formData, setFormData] = useState({});
  return (
    <Fragment>
      <Form className="form__check_settings">
        <Form.Group as={Row}>
          <Form.Label column sm={2}>
            Domains of free email providers
          </Form.Label>
          <Col sm={10}>
            <Form.Control type="text" />
          </Col>
        </Form.Group>
        <Form.Group as={Row}>
          <Form.Label column sm={2}>
            Risk score threshold for `Clear` status:
          </Form.Label>
          <Col sm={10}>
            <Form.Control type="text" />
          </Col>
        </Form.Group>
        <Form.Group as={Row} controlId="formHorizontalPassword">
          <Form.Label column sm={2}>
            Below Risk score threshold status:
          </Form.Label>
          <Col sm={10}>
            <Form.Control as="select">
              <option>Large select</option>
            </Form.Control>
          </Col>
        </Form.Group>
      </Form>
    </Fragment>
  );
};
